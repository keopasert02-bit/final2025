<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ ฟังก์ชัน: ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
function checkLogin() {
    if (empty($_SESSION['user_id'])) {
        redirectTo('/final/login.php');
    }
}

// ✅ ฟังก์ชัน: อนุญาตเฉพาะแอดมิน
function checkAdmin() {
    if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        redirectTo('/final/login.php?error=permission_denied', 'ທ່ານບໍ່ມີສິດເຂົ້າໜ້ານີ້');
    }
}

// ✅ ฟังก์ชัน: อนุญาตเฉพาะผู้ใช้ทั่วไป
function checkUser() {
    if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
        redirectTo('/final/login.php?error=permission_denied', 'ທ່ານບໍ່ມີສິດເຂົ້າໜ້ານີ້');
    }
}

// ✅ ฟังก์ชัน: redirect พร้อมตรวจ headers
function redirectTo($url, $alertMessage = '') {
    if (!headers_sent()) {
        header("Location: $url");
        exit;
    } else {
        if ($alertMessage) {
            echo "<script>alert('$alertMessage'); window.location.href = '$url';</script>";
        } else {
            echo "<script>window.location.href = '$url';</script>";
        }
        exit;
    }
}
