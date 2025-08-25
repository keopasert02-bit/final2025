<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ໂປຣໄຟລ໌ຜູ້ບໍລິຫານ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #fdf8f3;
      font-family: 'Noto Sans Lao', sans-serif;
    }
    .profile-card {
      max-width: 600px;
      margin: 70px auto;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      background: #fff;
      overflow: hidden;
      border-top: 5px solid #d4af37;
    }
    .card-header {
      background: linear-gradient(to right, #d4af37, #b8860b);
      color: white;
      text-align: center;
      padding: 25px 20px;
    }
    .card-header h4 {
      margin: 0;
      font-weight: bold;
      font-size: 1.5rem;
    }
    .card-body {
      padding: 30px;
    }
    .card-body p {
      font-size: 1.05rem;
      margin-bottom: 15px;
      color: #5a3921;
    }
    .card-body strong {
      color: #7a5230;
    }
    .card-footer {
      background: #f8f4ee;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      border-top: 1px solid #e0dcd5;
    }
    .btn-gold {
      background-color: #d4af37;
      border: none;
      color: white;
    }
    .btn-gold:hover {
      background-color: #c49e2e;
    }
    .btn-brown {
      background-color: #7a5230;
      border: none;
      color: white;
    }
    .btn-brown:hover {
      background-color: #68442a;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card profile-card">
    <div class="card-header">
      <h4><i class="bi bi-person-circle me-2"></i>ໂປຣໄຟລ໌ຜູ້ດູແລລະບົບ</h4>
    </div>
    <div class="card-body">
      <p><strong>👤 ຊື່ຜູ້ໃຊ້:</strong> <?= htmlspecialchars($admin['username']) ?></p>
      <p><strong>📧 ອີເມວ:</strong> <?= htmlspecialchars($admin['email']) ?></p>
      <p><strong>🎖️ ສະຖານະ:</strong> <?= htmlspecialchars(ucfirst($admin['role'])) ?></p>
      <p><strong>📅 ລົງທະບຽນເມື່ອ:</strong> <?= date("d/m/Y", strtotime($admin['created_at'])) ?></p>
    </div>
    <div class="card-footer">
      <a href="/final/settings/" class="btn btn-gold">
        <i class="bi bi-gear me-1"></i> ຕັ້ງຄ່າ
      </a>
      <a href="/final/admin_dashboard.php" class="btn btn-brown">
        <i class="bi bi-arrow-left-circle me-1"></i> ຍ້ອນກັບ
      </a>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>