<?php
$host = 'localhost';         // หรือ 127.0.0.1
$dbname = 'monk_management'; // ✅ เปลี่ยนชื่อฐานข้อมูลให้ตรงของคุณ
$username = 'root';          // ชื่อผู้ใช้ MySQL (ค่าเริ่มต้นของ XAMPP/MAMP)
$password = '';              // รหัสผ่าน (ค่าว่างสำหรับ XAMPP เริ่มต้น)

$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("ເຊື່ອມຕໍ່ຖານຂໍ້ມູນບໍ່ສຳເລັດ: " . $conn->connect_error);
}

// กำหนด charset เป็น UTF-8 เพื่อรองรับภาษาลาว/ไทย
$conn->set_charset("utf8mb4");
?>
