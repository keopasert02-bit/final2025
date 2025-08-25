<?php
// เริ่ม session (ถ้ายังไม่เริ่ม)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เคลียร์ session ทั้งหมด
session_unset();
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้า login
header("Location: login.php");
exit;
?>
