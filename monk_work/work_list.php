<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

include '../includes/header.php';

// ✅ สร้าง CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

// ✅ ดึงรายการงาน
$sql = "SELECT * FROM monk_work ORDER BY work_order ASC";
$stmt = $conn->query($sql);
$works = $stmt->fetch_all(MYSQLI_ASSOC);
?>

<!-- ✅ SweetAlert แจ้งเตือน -->
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
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-brown fw-bold"><i class="bi bi-list-task me-2"></i> ລາຍການວຽກວັດ</h3>
    <a href="work_add.php" class="btn btn-success shadow-sm">
      <i class="bi bi-plus-circle me-1"></i> ເພີ່ມວຽກໃໝ່
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover table-striped text-center align-middle">
        <thead>
          <tr>
            <th>ລຳດັບ</th>
            <th>ຊື່ວຽກ</th>
            <th>ລາຍລະອຽດ</th>
            <th>ລຳດັບວຽກ</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($works): ?>
            <?php foreach ($works as $i => $work): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td class="text-start fw-bold"><?= htmlspecialchars($work['work_name']) ?></td>
                <td class="text-start"><?= nl2br(htmlspecialchars($work['work_description'] ?? '')) ?></td>
                <td><?= $work['work_order'] ?></td>
                <td>
                  <a href="work_detail.php?id=<?= $work['work_id'] ?>" class="btn btn-info btn-sm" title="ລາຍລະອຽດ">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="work_edit.php?id=<?= $work['work_id'] ?>" class="btn btn-warning btn-sm" title="ແກ້ໄຂ">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $work['work_id'] ?>)" title="ລົບ">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">ບໍ່ພົບຂໍ້ມູນ</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ✅ SweetAlert2 ยืนยันการลบ -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
  Swal.fire({
    title: 'ລົບວຽກນີ້?',
    text: 'ຂໍ້ມູນຈະຖືກລຶບຖາວອນ ແລະບໍ່ສາມາດຍ້ອນກັບໄດ້',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'ລົບ',
    cancelButtonText: 'ຍົກເລີກ'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `work_delete.php?id=${id}&token=<?= $token ?>`;
    }
  });
}
</script>

<?php include '../includes/footer.php'; ?>
