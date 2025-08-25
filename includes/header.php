<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// ตรวจสอบการล็อกอิน
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] ?? 'ຜູ້ໃຊ້' : '';
?>
<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ລະບົບຈັດການວັດ - ລະບົບບໍລິຫານຈັດການຂໍ້ມູນວັດ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="ລະບົບຈັດການຂໍ້ມູນວັດແລະກິດຈະກໍາທາງສາສະໜາ">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #8b4513;
      --primary-dark: #6a340f;
      --primary-light: #a65c21;
      --secondary-color: #f5c242;
      --secondary-dark: #e0af30;
      --accent-color: #ffc107;
      --background-color: #fffaf0;
      --text-color: #333;
      --header-height: 75px;
      --gold-gradient: linear-gradient(135deg, #f8d568, #cb9b29);
      --wood-gradient: linear-gradient(135deg,rgb(139, 69, 19), #a65c21);
    }
    
    body {
      font-family: 'Noto Sans Lao', sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      overflow-x: hidden;
    }
    
    /* Enhanced Header Styles */
    .navbar {
      background: var(--wood-gradient);
      min-height: var(--header-height);
      padding: 0.5rem 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      position: relative;
      z-index: 100;
      transition: all 0.3s ease;
    }
    
    .navbar.scrolled {
      min-height: calc(var(--header-height) - 15px);
      padding: 0.3rem 1rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }
    
    .navbar::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gold-gradient);
      opacity: 0.8;
    }
    
    .navbar-brand {
      display: flex;
      align-items: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff !important;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      position: relative;
    }
    
    .navbar-brand:hover {
      transform: translateY(-2px);
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);
    }
    
    .navbar-brand i {
      font-size: 2rem;
      margin-right: 12px;
      color: var(--secondary-color);
      filter: drop-shadow(1px 1px 2px rgba(0, 0, 0, 0.3));
      background: radial-gradient(circle at center, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 70%);
      border-radius: 50%;
      padding: 5px;
    }
    
    .nav-link {
      color: rgba(255, 255, 255, 0.95) !important;
      font-weight: 500;
      padding: 0.6rem 1.2rem;
      border-radius: 6px;
      margin: 0 3px;
      position: relative;
      transition: all 0.3s ease;
      overflow: hidden;
      letter-spacing: 0.03em;
    }
    
    .nav-link::before {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 0;
      height: 2px;
      background-color: var(--secondary-color);
      transition: width 0.3s ease;
    }
    
    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }
    
    .nav-link:hover::before {
      width: 70%;
    }
    
    .nav-link.active {
      background-color: rgba(255, 255, 255, 0.2);
      color: var(--secondary-color) !important;
      font-weight: 600;
    }
    
    .nav-link.active::before {
      width: 80%;
      height: 3px;
    }
    
    .nav-link i {
      margin-right: 6px;
      vertical-align: -1px;
      transition: transform 0.3s ease;
    }
    
    .nav-link:hover i {
      transform: scale(1.2);
    }
    
    /* User Profile Styling */
    .user-profile {
      display: flex;
      align-items: center;
      color: #fff;
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 30px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }
    
    .user-profile:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.3);
    }
    
    .user-profile i {
      font-size: 1.5rem;
      margin-right: 10px;
      color: var(--secondary-color);
    }
    
    /* Enhanced animations */
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.15); }
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }
    
    .navbar-brand i {
      animation: pulse 3s infinite, float 6s infinite;
    }
    
    /* Dropdown styling */
   /* ✅ พื้นหลังโทนอ่อน สไตล์วัด */
.dropdown-menu {
  background-color: #fffaf0;
  border: none;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  transform: translateY(10px);
  opacity: 0;
  visibility: hidden;
  transition: all 0.35s ease;
  padding: 0.8rem 0;
  min-width: 220px;
  z-index: 9999;

  position: absolute;
}

/* ✅ แสดงเมื่อเปิด */
.dropdown-menu.show {
  transform: translateY(0);
  opacity: 1;
  visibility: visible;
}

/* ✅ รายการแต่ละรายการ */
.dropdown-item {
  font-weight: 500;
  padding: 0.65rem 1.5rem;
  display: flex;
  align-items: center;
  color: #5d4037;
  background-color: transparent;
  transition: all 0.3s ease;
  border-left: 4px solid transparent;
  
}

/* ✅ ไอคอน */
.dropdown-item i {
  margin-right: 10px;
  font-size: 1.1rem;
  color: #a65c21;
  transition: all 0.3s;
}

/* ✅ เวลา hover */
.dropdown-item:hover {
  background: linear-gradient(90deg, #f5c242, #e0af30);
  color: white;
  padding-left: 1.7rem;
  border-left: 4px solid #fff;
}

.dropdown-item:hover i {
  color: white;
  transform: scale(1.2);
}

    /* Login button styling */
    .btn-outline-light {
      border-width: 2px;
      border-radius: 30px;
      padding: 0.5rem 1.5rem;
      font-weight: 600;
      transition: all 0.3s;
      position: relative;
      overflow: hidden;
      z-index: 1;
    }
    
    .btn-outline-light::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.7s;
      z-index: -1;
    }
    
    .btn-outline-light:hover::before {
      left: 100%;
    }
    
    /* Enhanced Footer Styles */
    footer {
      background: linear-gradient(to right, #f7f5ec, #f0e8d6);
      border-top: 1px solid #e5e5e5;
      position: relative;
      overflow: hidden;
    }
    
    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gold-gradient);
      opacity: 0.8;
    }
    
    footer h5 {
      font-weight: 700;
      position: relative;
      display: inline-block;
      padding-bottom: 10px;
    }
    
    footer h5::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 3px;
      background: var(--gold-gradient);
      border-radius: 2px;
    }
    
    footer h6 {
      font-weight: 600;
      position: relative;
      display: inline-block;
      padding-bottom: 8px;
      margin-bottom: 15px;
    }
    
    footer h6::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background: var(--gold-gradient);
      border-radius: 2px;
    }
    
    footer .text-primary {
      color: var(--primary-color) !important;
    }
    
    footer .social-links {
      margin-top: 1.5rem;
      display: flex;
      gap: 15px;
    }
    
    footer .social-links a {
      font-size: 1.3rem;
      color: var(--primary-color);
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      border: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    footer .social-links a:hover {
      color: #fff;
      background: var(--primary-color);
      transform: translateY(-5px) rotate(10deg);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    footer ul li {
      margin-bottom: 12px;
      position: relative;
    }
    
    footer ul li a {
      transition: all 0.3s;
      display: inline-block;
      color: #636363 !important;
      padding-left: 5px;
    }
    
    footer ul li a:hover {
      color: var(--primary-color) !important;
      transform: translateX(8px);
    }
    
    footer ul li a i {
      margin-right: 8px;
      color: var(--primary-color);
    }
    
    footer .form-select {
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 0.5rem 2rem 0.5rem 1rem;
      box-shadow: none;
      font-size: 0.875rem;
      background-color: rgba(255, 255, 255, 0.8);
      transition: all 0.3s;
    }
    
    footer .form-select:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(245, 194, 66, 0.3);
      background-color: #fff;
    }
    
    footer hr {
      background: linear-gradient(to right, transparent, rgba(0, 0, 0, 0.1), transparent);
      height: 1px;
      opacity: 0.5;
    }
    
    footer .temple-info {
      display: flex;
      flex-direction: column;
      gap: 10px;
      background: rgba(255,255,255,0.4);
      border-radius: 15px;
      padding: 20px;
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
    
    /* Page Structure */
    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    
    .content-wrapper {
      flex: 1 0 auto;
      display: flex;
      flex-direction: column;
      padding-bottom: 2rem;
    }
    
    /* Footer Bottom */
    .footer-bottom {
      background-color: rgba(0,0,0,0.03);
      padding: 15px 0;
      border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Responsive tweaks */
    @media (max-width: 991px) {
      .navbar-collapse {
        background: var(--primary-dark);
        margin: 0 -1rem;
        padding: 1rem;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
      }
      
      .nav-link {
        margin: 5px 0;
      }
      
      .navbar-nav {
        padding-top: 10px;
        padding-bottom: 10px;
      }
      
      .user-profile {
        margin-top: 15px;
        justify-content: center;
      }
    }
    
    @media (max-width: 768px) {
      .navbar-brand {
        font-size: 1.3rem;
      }
      
      .navbar-brand i {
        font-size: 1.7rem;
      }
      
      footer {
        text-align: center;
      }
      
      footer h5::after,
      footer h6::after {
        left: 50%;
        transform: translateX(-50%);
      }
      
      footer .social-links {
        justify-content: center;
      }
      
      footer ul li a:hover {
        transform: none;
      }
    }
  </style>
</head>
<body>
  <div class="page-wrapper">
    <!-- Enhanced Header with visual effects -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
      <div class="container">
        <a class="navbar-brand fw-bold" href="/final/admin_dashboard.php" data-aos="fade-right" data-aos-delay="100">
          <i class="bi bi-flower1"></i> ລະບົບຈັດການຂໍ້ມູນວັດ
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto" data-aos="fade-down" data-aos-delay="200">
            <li class="nav-item">
              <a class="nav-link" href="/final/admin_dashboard.php"><i class="bi bi-house-fill"></i> ໜ້າຫຼັກ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/final/temples/list.php"><i class="bi bi-building"></i> ຂໍ້ມູນວັດ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/final/monks/list.php"><i class="bi bi-people-fill"></i> ພຣະສົງ</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/final/monk_transfers/list.php"><i class="bi bi-arrow-left-right"></i> ການຍ້າຍພຣະ</a>
            </li>
           <!-- เมนู dropdown: ກິດຈະກໍາ -->
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    <i class="bi bi-calendar-event"></i> ກິດຈະກໍາ
  </a>
  <ul class="dropdown-menu shadow rounded-3 border-0">
    <li><h6 class="dropdown-header text-secondary">📅 ກິດຈະກຳ</h6></li>
    <li><a class="dropdown-item" href="/final/temple_events/list_events.php">
      <i class="bi bi-calendar-check me-2 text-primary"></i>ງານບຸນ
    </a></li>
    <li><hr class="dropdown-divider"></li>
    <li><h6 class="dropdown-header text-secondary">🛠 ໜ້າວຽກວັດ</h6></li>
    <li><a class="dropdown-item" href="/final/monk_work/work_list.php">
      <i class="bi bi-arrow-repeat me-2 text-success"></i>ໜ້າວຽກວັດ
    </a></li>
  </ul>
</li>

<!-- เมนูລົງທະບຽນສະມາຊິກ -->
 <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    <i class="bi bi-people"></i>ບັນຊີສະມາຊິກພາຍລະບົບ
  </a>
  <ul class="dropdown-menu shadow rounded-3 border-0">
   
    <li><a class="dropdown-item" href="/final/admin/index.php">
      <i  class="bi bi-person-lock me-2 text-primary"></i>ຜູ້ຄວບຄຸມ
    </a></li>
    <li><hr class="dropdown-divider"></li>
   
    <li><a class="dropdown-item" href="/final/user/index.php">
      <i class="bi bi-people-fill me-2 text-success"></i>ຜູ້ໃຊ້ທົ່ວໄປ
    </a></li>
  </ul>
</li>


      
          </ul>

          <div class="d-flex align-items-center" data-aos="fade-left" data-aos-delay="300">
            <?php if ($isLoggedIn): ?>
              <div class="dropdown">
                <a class="user-profile dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                  <i class="bi bi-person-circle"></i>
                  <span><?= htmlspecialchars($userName) ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="/final/profile/index.php"><i class="bi bi-person-lines-fill"></i> ໂປຣໄຟລ໌</a></li>
                  <li><a class="dropdown-item" href="/final/settings/"><i class="bi bi-gear"></i> ຕັ້ງຄ່າ</a></li>
                  <li><hr class="dropdown-divider"></li>
                 <li><a class="dropdown-item" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> ອອກຈາກລະບົບ</a></li>

                </ul>
              </div>
            <?php else: ?>
              <a href="/final/login.php" class="btn btn-outline-light">
                <i class="bi bi-box-arrow-in-right"></i> ເຂົ້າສູ່ລະບົບ
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="container mt-4">
        <!-- เริ่มต้นเนื้อหาหลัก -->
        <div class="content-placeholder">
        