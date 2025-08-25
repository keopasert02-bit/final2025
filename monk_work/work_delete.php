<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$work_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : 0;
$token = $_GET['token'] ?? '';

if ($work_id <= 0 || $token !== ($_SESSION['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'ຂໍ້ມູນຫຼື Token ບໍ່ຖືກຕ້ອງ';
    header('Location: work_list.php');
    exit;
}

// ตรวจสอบว่ามีพระในงานนี้หรือไม่
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM monk_work_members WHERE work_id = ?");
$stmt->bind_param("i", $work_id);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

if ($total > 0) {
    $_SESSION['error'] = 'ບໍ່ສາມາດລົບວຽກນີ້ໄດ້ ເນື່ອງຈາກຍັງມີພຣະຢູ່ໃນວຽກ';
    header('Location: work_list.php');
    exit;
}

// ลบได้
$stmt = $conn->prepare("DELETE FROM monk_work WHERE work_id = ?");
$stmt->bind_param("i", $work_id);
if ($stmt->execute()) {
    $_SESSION['success'] = 'ລົບວຽກສຳເລັດແລ້ວ';
} else {
    $_SESSION['error'] = 'ລົບລົ້ມເຫຼວ: ' . $conn->error;
}
$stmt->close();

header('Location: work_list.php');
exit;
?>
