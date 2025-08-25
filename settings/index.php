<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$success = $error = "";

// ดึงข้อมูลผู้ใช้
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_username)) {
        $error = "ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້ໃໝ່";
    } elseif (!empty($new_password) && $new_password !== $confirm_password) {
        $error = "ລະຫັດຜ່ານໃໝ່ບໍ່ກົງກັນ";
    } else {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $new_username, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $new_username, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username;
            $success = "ອັບເດດສຳເລັດ!";
        } else {
            $error = "ມີບັນຫາໃນການອັບເດດ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ຕັ້ງຄ່າຜູ້ໃຊ້</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Noto Sans Lao', sans-serif;
    }
    h3 {
      color: #7a5230;
      font-weight: bold;
    }
    .form-label {
      color: #7a5230;
    }
    .btn-primary {
      background-color: #d4af37;
      border-color: #c39c34;
      color: #fff;
    }
    .btn-primary:hover {
      background-color: #c39c34;
      border-color: #b28a2f;
    }
    .btn-secondary {
      background-color: #7a5230;
      border-color: #6b472a;
      color: #fff;
    }
    .btn-secondary:hover {
      background-color: #6b472a;
      border-color: #5d3e25;
    }
  </style>
</head>
<body>

<div class="container mt-4" style="max-width: 600px;">
  <h3 class="mb-4"><i class="bi bi-gear"></i> ຕັ້ງຄ່າບັນຊີ</h3>

  <form method="post" action="" autocomplete="off">
    <div class="mb-3">
      <label for="username" class="form-label">ຊື່ຜູ້ໃຊ້ໃໝ່</label>
      <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">ລະຫັດຜ່ານໃໝ່ (ຖ້າຈະປ່ຽນ)</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="mb-3">
      <label for="confirm_password" class="form-label">ຢືນຢັນລະຫັດຜ່ານ</label>
      <input type="password" class="form-control" id="confirm_password" name="confirm_password">
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> ບັນທຶກການຕັ້ງຄ່າ</button>
    <a href="/final/profile/" class="btn btn-secondary">ຍ້ອນກັບ</a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>

<script>
<?php if ($success): ?>
Swal.fire({
  icon: 'success',
  title: 'ສຳເລັດ',
  text: '<?= $success ?>',
  confirmButtonColor: '#d4af37'
});
<?php elseif ($error): ?>
Swal.fire({
  icon: 'error',
  title: 'ຜິດພາດ',
  text: '<?= $error ?>',
  confirmButtonColor: '#a94442'
});
<?php endif; ?>
</script>
</body>
</html>
`
