<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();       // ✅ ตรวจสอบการล็อกอินก่อน
checkAdmin();       // ✅ ตรวจสอบสิทธิ์ admin
include '../includes/header.php'; // ✅ path ถูกต้อง

// ✅ ตรวจสอบ ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<script>
        alert('ID ບໍ່ຖືກຕ້ອງ');
        window.location = 'list.php';
    </script>";
    exit;
}

// ✅ ตรวจสอบว่ามีข้อมูลนี้ไหม
$stmt = $conn->prepare("SELECT * FROM monk_transfers WHERE transfer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transfer = $result->fetch_assoc();

if (!$transfer) {
    echo "<script>
        alert('ບໍ່ພົບຂໍ້ມູນການຍ້າຍ');
        window.location = 'list.php';
    </script>";
    exit;
}

// ✅ ลบข้อมูล
$stmt = $conn->prepare("DELETE FROM monk_transfers WHERE transfer_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'ສຳເລັດ!',
            text: 'ລຶບຂໍ້ມູນການຍ້າຍແລ້ວ',
            timer: 1500,
            showConfirmButton: false
        }).then(() => window.location = 'list.php');
    </script>";
} else {
    echo "<script>
        Swal.fire('Error', 'ບໍ່ສາມາດລຶບໄດ້', 'error').then(() => {
            window.location = 'list.php';
        });
    </script>";
}
?>
