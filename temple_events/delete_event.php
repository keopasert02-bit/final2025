<?php
// แสดง error (เฉพาะตอนพัฒนา)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// โหลด config และตรวจสอบสิทธิ์
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

// รับค่า ID จาก GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ตรวจสอบ ID
if ($id <= 0) {
    echo "<script>alert('ລະຫັດ ID ບໍ່ຖືກຕ້ອງ'); window.location = 'list_events.php';</script>";
    exit;
}

// ตรวจสอบก่อนลบ
$stmtCheck = $conn->prepare("SELECT id FROM temple_events WHERE id = ?");
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('ບໍ່ພົບກິດຈະກຳນີ້'); window.location = 'list_events.php';</script>";
    exit;
}

// ลบข้อมูล
$stmt = $conn->prepare("DELETE FROM temple_events WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // ถ้าลบสำเร็จ แสดง SweetAlert2 พร้อมฟอนต์ลาว
    echo "<!DOCTYPE html>
<html lang='lo'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>ລົບສຳເລັດ</title>

    <!-- ✅ Lao font -->
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;600;700&display=swap' rel='stylesheet'>

    <!-- ✅ SweetAlert2 -->
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <!-- ✅ Apply Lao font to entire page and SweetAlert -->
    <style>
        body,
        .swal2-popup,
        .swal2-title,
        .swal2-html-container,
        .swal2-styled {
            font-family: 'Noto Sans Lao', sans-serif;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'ລົບຂໍ້ມູນສຳເລັດ!',
    text: 'ຂໍ້ມູນກິດຈະກຳຖືກລົບອອກແລ້ວ',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location = 'list_events.php';
});
</script>
</body>
</html>";
} else {
    // ถ้าลบไม่สำเร็จ
    $errorMsg = htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
    echo "<script>alert('ລົບບໍ່ສຳເລັດ: {$errorMsg}'); window.location = 'list_events.php';</script>";
}
?>
