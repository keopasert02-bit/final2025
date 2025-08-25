<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['work_name']);
    $desc = trim($_POST['work_description']);
    $order = (int) $_POST['work_order'];

    if (empty($name)) {
        $_SESSION['error'] = 'ກະລຸນາປ້ອນຊື່ວຽກ';
    } else {
        $stmt = $conn->prepare("INSERT INTO monk_work (work_name, work_description, work_order) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $desc, $order);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'ເພີ່ມວຽກສຳເລັດ';
            header('Location: work_list.php');
            exit;
        } else {
            $_SESSION['error'] = 'ເພີ່ມລົ້ມເຫຼວ';
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<!-- ✅ Style -->
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
    font-weight: 500;
  }
  .btn-success {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-success:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4 fw-bold text-brown"><i class="bi bi-plus-circle me-2"></i>ເພີ່ມວຽກໃໝ່</h3>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <form method="post" class="form-card">
    <div class="mb-3">
      <label for="work_name" class="form-label">ຊື່ວຽກ *</label>
      <input type="text" name="work_name" id="work_name" class="form-control shadow-sm" required>
    </div>

    <div class="mb-3">
      <label for="work_description" class="form-label">ລາຍລະອຽດວຽກ</label>
      <textarea name="work_description" id="work_description" class="form-control shadow-sm" rows="3"></textarea>
    </div>

    <div class="mb-3">
      <label for="work_order" class="form-label">ລຳດັບວຽກ</label>
      <input type="number" name="work_order" id="work_order" class="form-control shadow-sm" value="1" min="1">
    </div>

    <button type="submit" class="btn btn-success">
      <i class="bi bi-check-circle me-1"></i> ບັນທຶກ
    </button>
    <a href="work_list.php" class="btn btn-outline-secondary ms-2">
      <i class="bi bi-arrow-left"></i> ກັບຄືນ
    </a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
