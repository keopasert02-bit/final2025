<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();       // ✅ ตรวจสอบการล็อกอินก่อน
checkAdmin();       // ✅ ตรวจสอบสิทธิ์ admin

// ດຶງຂໍ້ມູນຕາມເງື່ອນໄຂການຄົ້ນຫາ
$search = $_GET['search'] ?? '';
$type_filter = $_GET['type'] ?? '';
$like = '%' . $search . '%';

$params = [];
$where = [];

if (!empty($search)) {
    $where[] = "(LOWER(m.first_name) LIKE LOWER(?) OR LOWER(m.last_name) LIKE LOWER(?))";
    $params[] = $like;
    $params[] = $like;
}

if (!empty($type_filter) && in_array($type_filter, ['ພຣະ', 'ສາມະເນນ'])) {
    $where[] = "m.type = ?";
    $params[] = $type_filter;
}

$sql = "SELECT m.*, t.temple_name,
        TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) AS age,
        TIMESTAMPDIFF(YEAR, m.ordination_date, CURDATE()) AS ordination_years
        FROM monks m
        LEFT JOIN temples t ON m.temple_id = t.temple_id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY 
  CASE 
    WHEN m.type = 'ພຣະ' THEN 0
    WHEN m.type = 'ສາມະເນນ' THEN 1
    ELSE 2
  END,
  m.first_name ASC";


$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// ດຶງຂໍ້ມູນສະຖິຕິ
$total_monk = $conn->query("SELECT COUNT(*) AS total FROM monks")->fetch_assoc()['total'];
$phra_count = $conn->query("SELECT COUNT(*) AS total FROM monks WHERE type = 'ພຣະ'")->fetch_assoc()['total'];
$samanen_count = $conn->query("SELECT COUNT(*) AS total FROM monks WHERE type = 'ສາມະເນນ'")->fetch_assoc()['total'];

// ດຶງຂໍ້ມູນວັດ
$templeStmt = $conn->prepare("SELECT temple_name FROM temples WHERE temple_id = 1 LIMIT 1");
$templeStmt->execute();
$templeResult = $templeStmt->get_result();
$temple = $templeResult->fetch_assoc();
$templeName = $temple ? $temple['temple_name'] : 'ວັດສະພັງໝໍ້ ໄຊຍະຣາມ';
?>
<?php include '../includes/header.php' ?>
<style>
  body {
    background-color: #f8f9fa;
    
  }
  
  .main-header {
    background: linear-gradient(135deg, #8B4513, #A0522D);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 8px;
  }
  
  .page-title {
    color: #8B4513;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 3px solid #8B4513;
  }
  
  .stats-cards {
    margin-bottom: 2rem;
  }
  
  .stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-top: 4px solid;
    transition: transform 0.2s;
  }
  
  .stat-card:hover {
    transform: translateY(-2px);
  }
  
  .stat-card.total {
    border-top-color: #8B4513;
  }
  
  .stat-card.phra {
    border-top-color: #FFD700;
  }
  
  .stat-card.samanen {
    border-top-color: #6f42c1;
  }
  
  .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
  }
  
  .stat-label {
    color: #6c757d;
    font-weight: 500;
  }
  
  .search-form {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
  }
  
  .search-title {
    color: #8B4513;
    font-weight: 600;
    margin-bottom: 1rem;
  }
  
  .btn-primary {
    background-color: #8B4513;
    border-color: #8B4513;
    font-weight: 500;
  }
  
  .btn-primary:hover {
    background-color: #7a3c11;
    border-color: #7a3c11;
  }
  
  .btn-success {
    background-color: #28a745;
    border-color: #28a745;
    font-weight: 500;
  }
  
  .data-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
  }
  
  .table-header {
    background-color: #8B4513;
    color: white;
  }
  
  .table-header th {
    border: none;
    font-weight: 600;
    text-align: center;
    padding: 1rem;
  }
  
  .table tbody tr {
    transition: background-color 0.2s;
  }
  
  .table tbody tr:hover {
    background-color: #f8f9fa;
  }
  
  .table tbody td {
    padding: 1rem;
    vertical-align: middle;
  }
  
  .monk-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .default-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #D2B48C, #F5DEB3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8B4513;
    font-size: 1.5rem;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  
  .badge {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 20px;
  }
  
  .badge-phra {
    background-color: #FFD700;
    color: #8B4513;
  }
  
  .badge-samanen {
    background-color: #6f42c1;
    color: white;
  }
  
  .btn-control {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    border: none;
    font-size: 0.9rem;
    transition: all 0.2s;
  }
  
  .btn-control:hover {
    transform: translateY(-1px);
  }
  
  .btn-view {
    background-color: #007bff;
    color: white;
  }
  
  .btn-view:hover {
    background-color: #0056b3;
  }
  
  .btn-edit {
    background-color: #28a745;
    color: white;
  }
  
  .btn-edit:hover {
    background-color: #1e7e34;
  }
  
  .btn-delete {
    background-color: #dc3545;
    color: white;
  }
  
  .btn-delete:hover {
    background-color: #c82333;
  }
  
  .no-data {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
  }
  
  .no-data i {
    font-size: 3rem;
    color: #8B4513;
    margin-bottom: 1rem;
  }
  
  @media (max-width: 768px) {
    .main-header {
      padding: 1rem 0;
    }
    
    .table-responsive {
      font-size: 0.9rem;
    }
    
    .monk-img,
    .default-avatar {
      width: 50px;
      height: 50px;
    }
    
    .btn-control {
      width: 30px;
      height: 30px;
      font-size: 0.8rem;
    }
    
    .stat-number {
      font-size: 1.5rem;
    }
  }
</style>



<!-- ส่วนหัวของหน้า -->
<div class="main-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h2 class="mb-2"><?= htmlspecialchars($templeName) ?></h2>
        <p class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>ເມືອງໄຊເຊດຖາ ນະຄອນຫຼວງວຽງຈັນ</p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="export_pdf.php?search=<?= urlencode($search) ?>&type=<?= urlencode($type_filter) ?>" 
           target="_blank" class="btn btn-light">
          <i class="bi bi-file-earmark-pdf-fill me-2"></i>ສົ່ງອອກ PDF
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <h3 class="page-title">
    <i class="bi bi-person-vcard-fill me-2"></i>ລາຍຊື່ພຣະສົງ
  </h3>

  <!-- ສະຖິຕິພຣະສົງ -->
  <div class="stats-cards">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="stat-card total">
          <div class="stat-number"><?= number_format($total_monk) ?></div>
          <div class="stat-label">ພຣະສົງທັງໝົດ</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card phra">
          <div class="stat-number"><?= number_format($phra_count) ?></div>
          <div class="stat-label">ພຣະ</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-card samanen">
          <div class="stat-number"><?= number_format($samanen_count) ?></div>
          <div class="stat-label">ສາມະເນນ</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ຟອມຄົ້ນຫາ -->
  <div class="search-form">
    <h5 class="search-title">
      <i class="bi bi-search me-2"></i>ຄົ້ນຫາຂໍ້ມູນພຣະສົງ
    </h5>
    <form method="GET" class="row g-3">
      <div class="col-md-5">
        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
               class="form-control" placeholder="ຄົ້ນຫາຕາມຊື່ ຫຼື ນາມສະກຸນ...">
      </div>
      <div class="col-md-3">
        <select name="type" class="form-select">
          <option value="">-- ທັງໝົດ --</option>
          <option value="ພຣະ" <?= ($_GET['type'] ?? '') == 'ພຣະ' ? 'selected' : '' ?>>ພຣະ</option>
          <option value="ສາມະເນນ" <?= ($_GET['type'] ?? '') == 'ສາມະເນນ' ? 'selected' : '' ?>>ສາມະເນນ</option>
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary me-2">
          <i class="bi bi-search me-1"></i>ຄົ້ນຫາ
        </button>
        <?php if (!empty($_GET['search']) || !empty($_GET['type'])): ?>
        <a href="list.php" class="btn btn-outline-secondary">
          <i class="bi bi-x-circle me-1"></i>ລ້າງ
        </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- ປຸ່ມເພີ່ມຂໍ້ມູນ -->
  <div class="mb-3">
    <a href="add.php" class="btn btn-success">
      <i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນພຣະສົງ
    </a>
  </div>

  <!-- ຕາຕະລາງຂໍ້ມູນພຣະສົງ -->
  <div class="data-table">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-header">
          <tr>
            <th width="60">ລຳດັບ</th>
            <th width="80">ຮູບພາບ</th>
            <th>ຊື່-ນາມສະກຸນ</th>
            <th>ວັນເກີດ</th>
            <th>ບ້ານ/ເມືອງ/ແຂວງ</th>
            <th>ວັດສັງກັດ</th>
            <th>ເບີໂທ</th>
            <th>ປະເພດ</th>
            <th>ວັນບວດ</th>
            <th width="120">ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 1; 
          if ($result->num_rows == 0): 
          ?>
            <tr>
              <td colspan="10">
                <div class="no-data">
                  <i class="bi bi-search"></i>
                  <h4>ບໍ່ພົບຂໍ້ມູນພຣະສົງ</h4>
                  <?php if (!empty($search) || !empty($type_filter)): ?>
                    <p>ກະລຸນາລອງຄົ້ນຫາດ້ວຍຄຳສັບອື່ນ ຫຼື ສະແດງທັງໝົດ</p>
                    <a href="list.php" class="btn btn-primary">ສະແດງທັງໝົດ</a>
                  <?php else: ?>
                    <p>ຍັງບໍ່ມີຂໍ້ມູນພຣະສົງໃນລະບົບ</p>
                    <a href="add.php" class="btn btn-success">ເພີ່ມຂໍ້ມູນພຣະສົງ</a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td class="text-center fw-bold"><?= $i++ ?></td>
              <td class="text-center">
                <?php if (!empty($row['image_path']) && file_exists('../uploads/' . $row['image_path'])): ?>
                  <img src="../uploads/<?= htmlspecialchars($row['image_path']) ?>" 
                       class="monk-img" alt="<?= htmlspecialchars($row['first_name']) ?>">
                <?php else: ?>
                  <div class="default-avatar">
                    <i class="bi bi-person-fill"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="fw-bold"><?= htmlspecialchars($row['first_name']) ?></div>
                <div class="text-muted"><?= htmlspecialchars($row['last_name']) ?></div>
              </td>
              <td>
                <div><?= date('d/m/Y', strtotime($row['birth_date'])) ?></div>
                <small class="text-muted">
                  <?= isset($row['age']) ? "ອາຍຸ " . $row['age'] . " ປີ" : "" ?>
                </small>
              </td>
              <td>
                <div><?= htmlspecialchars($row['village']) ?></div>
                <small class="text-muted">
                  <?= htmlspecialchars($row['district']) ?>, <?= htmlspecialchars($row['province']) ?>
                </small>
              </td>
              <td><?= htmlspecialchars($row['temple_name'] ?? 'ບໍ່ລະບຸ') ?></td>
              <td>
                <?php if (!empty($row['phone'])): ?>
                  <a href="tel:<?= htmlspecialchars($row['phone']) ?>" class="text-decoration-none">
                    <?= htmlspecialchars($row['phone']) ?>
                  </a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($row['type'] === 'ພຣະ'): ?>
                  <span class="badge badge-phra">ພຣະ</span>
                <?php elseif ($row['type'] === 'ສາມະເນນ'): ?>
                  <span class="badge badge-samanen">ສາມະເນນ</span>
                <?php else: ?>
                  <span class="badge bg-secondary">-</span>
                <?php endif; ?>
              </td>
              <td>
                <div><?= date('d/m/Y', strtotime($row['ordination_date'])) ?></div>
                <small class="text-muted">
                  <?= isset($row['ordination_years']) ? $row['ordination_years'] . " ປີ" : "" ?>
                </small>
              </td>
             <td class="text-center">
  <div class="btn-group" role="group" aria-label="Action buttons">
    <a href="view.php?id=<?= $row['monk_id'] ?>" class="btn btn-info btn-sm" title="ເບິ່ງຂໍ້ມູນ">
      <i class="bi bi-eye"></i>
    </a>
    <a href="edit.php?id=<?= $row['monk_id'] ?>" class="btn btn-success btn-sm" title="ແກ້ໄຂ">
      <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-danger btn-sm" title="ລຶບ"
            onclick="confirmDelete(<?= $row['monk_id'] ?>, '<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>')">
      <i class="bi bi-trash"></i>
    </button>
  </div>
</td>

            </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(monkId, monkName) {
  Swal.fire({
    title: 'ຢືນຢັນການລຶບ?',
    text: `ທ່ານຕ້ອງການລຶບຂໍ້ມູນຂອງ ${monkName}?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'ລຶບ',
    cancelButtonText: 'ຍົກເລີກ'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `delete.php?id=${monkId}`;
    }
  });
}
</script>

<?php include '../includes/footer.php'; ?>