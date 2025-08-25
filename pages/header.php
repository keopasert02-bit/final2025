<?php
include '../config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title><?php echo isset($page_title) ? $page_title : 'ວັດສະພັງໝໍ້ ໄຊຍະຣາມ'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'ເວັບໄຊທ໌ວັດ ສຳລັບຈັດການຂໍ້ມູນ ແລະ ກິດຈະກໍາທາງສາສະໜາ'; ?>">

  <!-- Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root {
      --temple-gold: #f5c242;
      --temple-brown: #8b4513;
    }

    body {
      font-family: 'Noto Sans Lao', sans-serif;
      background: #fffdf6;
      color: #2c1810;
    }

    .navbar {
      background: linear-gradient(135deg, #8b4513, #a65c21, #c17631);
      padding: 1rem 0;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      font-size: 2rem;
      color: white !important;
      font-weight: bold;
    }

    .nav-link {
      color: #fff !important;
      padding: 0.8rem 1.2rem;
      font-weight: 500;
    }

    .nav-link:hover,
    .nav-link.active {
      background: var(--temple-gold);
      color: #000 !important;
      border-radius: 12px;
    }

    .dropdown-menu {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<?php $current = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <i class="bi bi-flower1"></i> ວັດສະພັງໝໍ້ ໄຊຍະຣາມ
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?= $current == 'index.php' ? 'active' : '' ?>" href="index.php">
            <i class="bi bi-house-fill me-1"></i> ໜ້າຫຼັກ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current == 'history.php' ? 'active' : '' ?>" href="history.php">
            <i class="bi bi-book-half me-1"></i> ປະຫວັດວັດ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current == 'member.php' ? 'active' : '' ?>" href="member.php">
            <i class="bi bi-person-bounding-box me-1"></i> ພຣະສົງ
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $current == 'contact.php' ? 'active' : '' ?>" href="contact.php">
            <i class="bi bi-telephone-fill me-1"></i> ຕິດຕໍ່
          </a>
        </li>
       <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle <?= in_array($current, ['temple_events_public.php', 'monk_work.php', 'temple_gallery.php']) ? 'active' : '' ?>" 
     href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-calendar-event-fill me-1"></i> ກິດຈະກໍາ
  </a>
  <ul class="dropdown-menu dropdown-menu-end shadow rounded-4 animate__animated animate__fadeIn">
    <li>
      <a class="dropdown-item d-flex align-items-center gap-2" href="temple_events_public.php">
        <i class="bi bi-stars text-warning"></i> ງານບຸນ
      </a>
    </li>
    <li>
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a class="dropdown-item d-flex align-items-center gap-2" href="#" onclick="showLoginAlert(event)">
          <i class="bi bi-briefcase text-primary"></i> ໜ້າວຽກວັດ
        </a>
      <?php else: ?>
        <a class="dropdown-item d-flex align-items-center gap-2" href="monk_work.php">
          <i class="bi bi-briefcase text-primary"></i> ໜ້າວຽກວັດ
        </a>
      <?php endif; ?>
    </li>
  </ul>
</li>



       <!-- LOGIN/LOGOUT -->
<?php if (isset($_SESSION['user_id'])): ?>
  <li class="nav-item">
    <a class="nav-link text-warning" href="#" onclick="logoutConfirm(event)">
      <i class="bi bi-box-arrow-right me-1"></i> ອອກຈາກລະບົບ
    </a>
  </li>
<?php else: ?>
  <li class="nav-item">
    <a class="nav-link text-light" href="../login.php">
      <i class="bi bi-box-arrow-in-right me-1"></i> ເຂົ້າລະບົບ
    </a>
  </li>
<?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<!-- Main Content Wrapper -->
<div class="content-wrapper">
  <div class="container py-5">
