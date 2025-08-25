<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();       
checkAdmin();       

if (session_status() === PHP_SESSION_NONE) session_start();

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['csrf_token'];

include '../includes/header.php';

$stmt = $conn->query("SELECT * FROM temples ORDER BY temple_id DESC");
?>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Custom Style -->
<style>
  .text-brown {
    color: #7a5c20;
  }
  .btn-success {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-success:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
  .card-temple {
    background: linear-gradient(to bottom right, #fdf5e6, #fff);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }
  .table th {
    background: linear-gradient(45deg, #d4af37, #c59d28);
    color: #fff;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-brown fw-bold"><i class="bi bi-bank2 me-2"></i>ລາຍຊື່ວັດ</h3>
    <a href="add.php" class="btn btn-success shadow-sm">
      <i class="bi bi-plus-circle me-1"></i> ເພີ່ມວັດ
    </a>
  </div>

  <div class="card-temple">
    <div class="table-responsive">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead>
          <tr>
            <th>ລຳດັບ</th>
            <th>ຊື່ວັດ</th>
            <th>ບ້ານ</th>
            <th>ເມືອງ</th>
            <th>ແຂວງ</th>
            <th>ຈັດການ</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; while ($row = $stmt->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['temple_name']) ?></td>
              <td><?= htmlspecialchars($row['village']) ?></td>
              <td><?= htmlspecialchars($row['district']) ?></td>
              <td><?= htmlspecialchars($row['province']) ?></td>
              <td>
                <a href="edit.php?id=<?= $row['temple_id'] ?>" class="btn btn-sm btn btn-primary" title="ແກ້ໄຂ">
                  <i class="bi bi-pencil"></i>
                </a>
                <button onclick="confirmDelete(<?= $row['temple_id'] ?>)" class="btn btn-sm btn btn-danger" title="ລົບ">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ✅ Script ลบ -->
<script>
function confirmDelete(id) {
  Swal.fire({
    title: 'ຢືນຢັນການລົບ?',
    text: 'ຂໍ້ມູນຈະຖືກລຶບຖາວອນ!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'ລົບ',
    cancelButtonText: 'ຍົກເລີກ'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = `delete.php?id=${id}&token=<?= $token ?>`;
    }
  });
}
</script>

<?php include '../includes/footer.php'; ?>
