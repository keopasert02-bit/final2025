<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$work_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;
if ($work_id <= 0) {
    $_SESSION['error'] = 'ລະຫັດບໍ່ຖືກຕ້ອງ';
    header('Location: work_list.php');
    exit;
}

// ดึงข้อมูลงาน
$stmt = $conn->prepare("SELECT * FROM monk_work WHERE work_id = ?");
$stmt->bind_param("i", $work_id);
$stmt->execute();
$work = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$work) {
    $_SESSION['error'] = 'ບໍ່ພົບວຽກ';
    header('Location: work_list.php');
    exit;
}

// ดึงสมาชิก
$stmt = $conn->prepare("SELECT mwm.id, m.first_name, m.last_name, m.type, mwm.sequence 
                        FROM monk_work_members mwm 
                        JOIN monks m ON mwm.monk_id = m.monk_id 
                        WHERE mwm.work_id = ?
                        ORDER BY mwm.sequence ASC, m.first_name ASC");
$stmt->bind_param("i", $work_id);
$stmt->execute();
$members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include '../includes/header.php';
?>

<!-- ✅ SweetAlert2 -->
<?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    Swal.fire({
      icon: '<?= isset($_SESSION['success']) ? 'success' : 'error' ?>',
      title: '<?= isset($_SESSION['success']) ? 'ສຳເລັດ!' : 'ຜິດພາດ!' ?>',
      text: '<?= $_SESSION['success'] ?? $_SESSION['error'] ?>',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
  <?php unset($_SESSION['success'], $_SESSION['error']); ?>
<?php endif; ?>

<!-- ✅ Custom Style -->
<style>
  .text-brown { color: #7a5c20; }
  .btn-success {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-success:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
  .card {
    border-radius: 15px;
    background: linear-gradient(to bottom right, #fdf5e6, #fff);
    border: 1px solid #eee;
  }
  .table th {
    background: linear-gradient(45deg, #d4af37, #c59d28);
    color: white;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="text-brown fw-bold mb-3">
    <i class="bi bi-eye me-2"></i>ລາຍລະອຽດວຽກ: <?= htmlspecialchars($work['work_name']) ?>
  </h3>

  <div class="mb-3"><strong>ລາຍລະອຽດ:</strong><br><?= nl2br(htmlspecialchars($work['work_description'])) ?></div>

  <div class="d-flex gap-2 mb-3">
    <a href="member_add.php?work_id=<?= $work_id ?>" class="btn btn-success">
      <i class="bi bi-person-plus me-1"></i> ເພີ່ມພຣະໃນວຽກ
    </a>
    <a href="work_list.php" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> ກັບຄືນ
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>ຊື່ພຣະ</th>
            <th>ປະເພດ</th>
            <th>ໜ່ວຍ</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $i => $m): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?></td>
              <td><?= $m['type'] === 'ພຣະ' ? 'ພຣະ' : ($m['type'] === 'ສາມະເນນ' ? 'ສາມະເນນ' : 'ອື່ນໆ') ?></td>
              <td><?= $m['sequence'] ?></td>
              <td>
                <a href="member_edit.php?id=<?= $m['id'] ?>&work_id=<?= $work_id ?>" class="btn btn-warning btn-sm" title="ແກ້ໄຂ">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $m['id'] ?>, <?= $work_id ?>)" title="ລົບ">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($members)): ?>
            <tr><td colspan="5">ຍັງບໍ່ມີພຣະໃນວຽກ</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ✅ SweetAlert2 Delete -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id, workId) {
  Swal.fire({
    title: 'ລົບພຣະນີ້?',
    text: 'ຂໍ້ມູນຈະຖືກລຶບຖາວອນ!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'ລົບ',
    cancelButtonText: 'ຍົກເລີກ'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `member_delete.php?id=${id}&work_id=${workId}`;
    }
  });
}
</script>

<?php include '../includes/footer.php'; ?>
