<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$work_id = isset($_GET['work_id']) ? (int) $_GET['work_id'] : 0;

if ($id <= 0 || $work_id <= 0) {
    $_SESSION['error'] = 'ຂໍ້ມູນຜິດພາດ';
    header("Location: work_detail.php?id=$work_id");
    exit;
}

// ตรวจสอบว่าพระมีอยู่จริงในงาน
$stmt = $conn->prepare("SELECT mwm.id, m.first_name, m.last_name 
                        FROM monk_work_members mwm
                        JOIN monks m ON mwm.monk_id = m.monk_id
                        WHERE mwm.id = ? AND mwm.work_id = ?");
$stmt->bind_param("ii", $id, $work_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'ບໍ່ພົບຂໍ້ມູນ';
    header("Location: work_detail.php?id=$work_id");
    exit;
}
$monk = $result->fetch_assoc();
$stmt->close();

// ดำเนินการลบ
$stmt = $conn->prepare("DELETE FROM monk_work_members WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'ລົບພຣະ ' . htmlspecialchars($monk['first_name'] . ' ' . $monk['last_name']) . ' ອອກຈາກວຽກແລ້ວ';
} else {
    $_SESSION['error'] = 'ລົບບໍ່ສຳເລັດ';
}
$stmt->close();

// กลับไปยังรายละเอียดงาน
header("Location: work_detail.php?id=$work_id");
exit;
