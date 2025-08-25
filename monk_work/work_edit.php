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

$stmt = $conn->prepare("SELECT * FROM monk_work WHERE work_id = ?");
$stmt->bind_param("i", $work_id);
$stmt->execute();
$work = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$work) {
    $_SESSION['error'] = 'ບໍ່ພົບຂໍ້ມູນ';
    header('Location: work_list.php');
    exit;
}

// ✅ เมื่อ submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['work_name']);
    $desc = trim($_POST['work_description']);
    $order = (int) $_POST['work_order'];

    if (empty($name)) {
        $_SESSION['error'] = 'ກະລຸນາປ້ອນຊື່ວຽກ';
    } else {
        $stmt = $conn->prepare("UPDATE monk_work SET work_name = ?, work_description = ?, work_order = ? WHERE work_id = ?");
        $stmt->bind_param("ssii", $name, $desc, $order, $work_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'ແກ້ໄຂວຽກສຳເລັດ';
            header('Location: work_list.php');
            exit;
        } else {
            $_SESSION['error'] = 'ແກ້ໄຂລົ້ມເຫຼວ';
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<!-- ✅ STYLE -->
<style>
  .text-brown {
    color: #7a5c20;
  }
  .form-card {
    background: linear-gradient(to bottom right, #fff8e1, #fff);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .form-label {
    color: #7a5c20;
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

<!-- ✅ CONTENT -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4 fw-bold text-brown">
    <i class="bi bi-pencil-square me-2"></i> ແກ້ໄຂວຽກ
  </h3>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <form method="post" class="form-card">
    <div class="mb-3">
      <label for="work_name" class="form-label">ຊື່ວຽກ *</label>
      <input type="text" name="work_name" id="work_name" class="form-control shadow-sm"
             value="<?= htmlspecialchars($work['work_name']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="work_description" class="form-label">ລາຍລະອຽດວຽກ</label>
      <textarea name="work_description" id="work_description" class="form-control shadow-sm" rows="4"><?= htmlspecialchars($work['work_description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="work_order" class="form-label">ລຳດັບວຽກ</label>
      <input type="number" name="work_order" id="work_order" class="form-control shadow-sm"
             value="<?= $work['work_order'] ?>" min="1">
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save me-1"></i> ບັນທຶກ
    </button>
    <a href="work_list.php" class="btn btn-outline-secondary ms-2">
      <i class="bi bi-arrow-left"></i> ກັບຄືນ
    </a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
