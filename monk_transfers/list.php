<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

include '../includes/header.php';

// ✅ ตั้งค่าการแบ่งหน้า
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// ✅ ตัวแปรค้นหาและกรอง
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$transfer_type = isset($_GET['transfer_type']) ? $_GET['transfer_type'] : '';
$search_condition = '';
$type_condition = '';
$bind_types = '';
$bind_values = [];

// ✅ เงื่อนไขการค้นหา (ชื่อพระ, วัด)
if (!empty($search)) {
    $search_condition .= " AND (m.first_name LIKE ? OR m.last_name LIKE ? OR t.temple_name LIKE ?)";
    $search_param = "%$search%";
    $bind_types .= 'sss';
    array_push($bind_values, $search_param, $search_param, $search_param);
}

// ✅ เงื่อนไขการกรองประเภทการย้าย
if (!empty($transfer_type)) {
    $type_condition .= " AND mt.transfer_type = ?";
    $bind_types .= 's';
    $bind_values[] = $transfer_type;
}

// ✅ ดึงจำนวนรายการทั้งหมด
$count_sql = "SELECT COUNT(*) AS total FROM monk_transfers mt
    LEFT JOIN monks m ON mt.monk_id = m.monk_id
    LEFT JOIN temples t ON mt.temple_id = t.temple_id
    WHERE 1=1 $search_condition $type_condition";

$count_stmt = $conn->prepare($count_sql);
if ($bind_types !== '') {
    $count_stmt->bind_param($bind_types, ...$bind_values);
}
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// ✅ ดึงข้อมูลรายการ พร้อมจัดลำดับให้ "ຍ້າຍເຂົ້າ" ขึ้นก่อน
$sql = "SELECT mt.*, m.first_name, m.last_name, t.temple_name,
        TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) AS age,
        d.temple_name AS destination_temple
        FROM monk_transfers mt
        LEFT JOIN monks m ON mt.monk_id = m.monk_id
        LEFT JOIN temples t ON mt.temple_id = t.temple_id
        LEFT JOIN temples d ON mt.goto_temple = d.temple_id
        WHERE 1=1 $search_condition $type_condition
        ORDER BY 
            FIELD(mt.transfer_type, 'ຍ້າຍເຂົ້າ') DESC,  -- ✅ เรียงให้ 'ຍ້າຍເຂົ້າ' อยู่บนสุด
            mt.transfer_date DESC                       -- ✅ แล้วเรียงตามวันที่
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$bind_types .= 'ii';
array_push($bind_values, $records_per_page, $offset);
$stmt->bind_param($bind_types, ...$bind_values);
$stmt->execute();
$result = $stmt->get_result();

// ✅ แสดงข้อความถ้ามี
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<!-- ✅ Style โทนวัด -->
<style>
  body {
    background-color: #fdf8ef;
  }

  h3 {
    background: linear-gradient(to right, #d4af37, #c59d28);
    padding: 12px 20px;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  }

  .btn-success {
    background-color: #c59d28;
    border-color: #c59d28;
    color: white;
  }

  .btn-success:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }

  .btn-info {
    background-color: #cc9933;
    border-color: #cc9933;
    color: white;
  }

  .btn-info:hover {
    background-color: #a6752a;
  }

  .btn-danger {
    background-color: #d9534f;
    border-color: #d9534f;
  }

  .table thead {
    background: linear-gradient(45deg, #d4af37, #c59d28);
    color: white;
  }

  .pagination .page-link {
    color: #7a5c20;
  }

  .pagination .page-item.active .page-link {
    background-color: #c59d28;
    border-color: #c59d28;
    color: white;
  }

  .modal-header {
    background-color: #c59d28;
    color: white;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4">
  <h3 class="mb-4 fw-bold"><i class="bi bi-arrow-left-right me-2"></i>ຂໍ້ມູນການຍ້າຍພຣະ</h3>

  <?php if (!empty($message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $message ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row mb-3">
    <div class="col-md-8 d-flex gap-2">
      <a href="add.php" class="btn btn-success d-flex align-items-center">
        <i class="bi bi-plus-circle me-1"></i> ເພີ່ມຂໍ້ມູນການຍ້າຍ
      </a>
      <a href="transfer_export.php" class="btn btn-danger d-flex align-items-center">
        <i class="bi bi-file-earmark-pdf me-1"></i> ສົ່ງອອກລາຍງານ
      </a>
      <a href="transfer_select.php" class="btn btn-info">
        📄 ເລືອກພຣະທີ່ຍ້າຍອອກ
      </a>
    </div>
    <div class="col-md-4">
      <form method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="ຄົ້ນຫາ..." value="<?= htmlspecialchars($search) ?>">
        <select name="transfer_type" class="form-select me-2" style="max-width:130px;">
          <option value="">ທັງໝົດ</option>
          <option value="ຍ້າຍເຂົ້າ" <?= $transfer_type === 'ຍ້າຍເຂົ້າ' ? 'selected' : '' ?>>ຍ້າຍເຂົ້າ</option>
          <option value="ຍ້າຍອອກ" <?= $transfer_type === 'ຍ້າຍອອກ' ? 'selected' : '' ?>>ຍ້າຍອອກ</option>
        </select>
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center">
      <thead>
        <tr>
          <th>ລຳດັບ</th>
          <th>ຊື່ພຣະ</th>
          <th>ປະເພດ</th>
          <th>ວັນທີຍ້າຍ</th>
          <th>ວັດປັດຈຸບັນ</th>
          <th>ວັດປາຍທາງ</th>
          <th>ເຫດຜົນ</th>
          <th>ຈັດການ</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): $i = $offset + 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td>
              <span class="badge <?= $row['transfer_type'] === 'ຍ້າຍເຂົ້າ' ? 'bg-success' : 'bg-danger' ?>">
                <?= $row['transfer_type'] ?>
              </span>
            </td>
            <td><?= date('d/m/Y', strtotime($row['transfer_date'])) ?></td>
            <td><?= htmlspecialchars($row['temple_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['goto_temple'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['reason']) ?></td>
            <td>
             
              <a href="edit.php?id=<?= $row['transfer_id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $row['transfer_id'] ?>">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="8">ບໍ່ພົບຂໍ້ມູນ</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav><ul class="pagination justify-content-center">
      <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&transfer_type=<?= urlencode($transfer_type) ?>">ກ່ອນໜ້າ</a>
      </li>
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&transfer_type=<?= urlencode($transfer_type) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&transfer_type=<?= urlencode($transfer_type) ?>">ຕໍ່ໄປ</a>
      </li>
    </ul></nav>
  <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ຢືນຢັນການລຶບ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">ທ່ານແນ່ໃຈບໍ່ວ່າຈະລຶບລາຍການນີ້?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ຍົກເລີກ</button>
        <a href="#" id="deleteLink" class="btn btn-danger">ລຶບ</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var deleteModal = document.getElementById('deleteModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var link = document.getElementById('deleteLink');
    link.href = 'delete.php?id=' + id;
  });
});
</script>

<?php include '../includes/footer.php'; ?>
