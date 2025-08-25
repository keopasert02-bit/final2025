<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$work_id = isset($_GET['work_id']) ? (int) $_GET['work_id'] : 0;

if ($id <= 0 || $work_id <= 0) {
    $_SESSION['error'] = 'ຂໍ້ມູນບໍ່ຖືກຕ້ອງ';
    header("Location: work_detail.php?id=$work_id");
    exit;
}

// ดึงข้อมูลสมาชิก
$stmt = $conn->prepare("SELECT mwm.id, mwm.sequence, m.first_name, m.last_name
                        FROM monk_work_members mwm
                        JOIN monks m ON mwm.monk_id = m.monk_id
                        WHERE mwm.id = ? AND mwm.work_id = ?");
$stmt->bind_param("ii", $id, $work_id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$member) {
    $_SESSION['error'] = 'ບໍ່ພົບຂໍ້ມູນ';
    header("Location: work_detail.php?id=$work_id");
    exit;
}

// เมื่อมีการ submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sequence = (int) $_POST['sequence'];

    $stmt = $conn->prepare("UPDATE monk_work_members SET sequence = ? WHERE id = ?");
    $stmt->bind_param("ii", $sequence, $id);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'ແກ້ໄຂລຳດັບສຳເລັດ';
    } else {
        $_SESSION['error'] = 'ແກ້ໄຂລົ້ມເຫຼວ';
    }
    header("Location: work_detail.php?id=$work_id");
    exit;
}

include '../includes/header.php';
?>

<!-- ✅ SweetAlert2 Notification -->
<?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: '<?= isset($_SESSION['success']) ? 'success' : 'error' ?>',
  title: '<?= isset($_SESSION['success']) ? 'ສຳເລັດ' : 'ຜິດພາດ' ?>',
  text: '<?= $_SESSION['success'] ?? $_SESSION['error'] ?>',
  timer: 2000,
  showConfirmButton: false
});
</script>
<?php unset($_SESSION['success'], $_SESSION['error']); ?>
<?php endif; ?>

<!-- ✅ Custom Style -->
<style>
  .text-brown {
    color: #7a5c20;
  }
  .form-card {
    background: linear-gradient(to bottom right, #fdf5e6, #fff);
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .form-label {
    color: #7a5c20;
    font-weight: 500;
  }
  .btn-primary {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-primary:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="text-brown fw-bold mb-4">
    <i class="bi bi-pencil-square me-2"></i>
    ແກ້ໄຂລຳດັບ: <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
  </h3>

  <form method="post" class="form-card p-4">
    <div class="mb-3">
      <label for="sequence" class="form-label">ລຳດັບໜ່ວຍໃໝ່</label>
      <input type="number" name="sequence" id="sequence" class="form-control shadow-sm" value="<?= $member['sequence'] ?>" required min="1">
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> ບັນທຶກ
      </button>
      <a href="work_detail.php?id=<?= $work_id ?>" class="btn btn-outline-secondary ms-2">
        <i class="bi bi-arrow-left"></i> ກັບຄືນ
      </a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
