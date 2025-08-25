<?php
session_start();
require_once '../config.php';
require_once '../auth.php';
checkAdmin();

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$token = $_POST['token'] ?? '';

if (!$id || !is_numeric($id) || $token !== $_SESSION['csrf_token']) {
    echo json_encode(['status'=>'error', 'message'=>'ຂໍ້ມູນບໍ່ຖືກຕ້ອງ ຫຼື token ບໍ່ຖືກຕ້ອງ']);
    exit;
}

$id = intval($id);

// ກວດສອບຈໍານວນ admin
$total_admin_result = $conn->query("SELECT COUNT(*) as total_admin FROM users WHERE role='admin'");
$total_admin = $total_admin_result->fetch_assoc()['total_admin'];

if ($total_admin <= 1) {
    echo json_encode(['status'=>'error', 'message'=>'ບໍ່ສາມາດລຶບແອັດມິນຄົນສຸດທ້າຍໄດ້']);
    exit;
}

// ກວດສອບ admin ທີ່ຈະລຶບ
$stmt = $conn->prepare("SELECT username FROM users WHERE id=? AND role='admin'");
$stmt->bind_param("i", $id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['status'=>'error', 'message'=>'ບໍ່ພົບແອັດມິນນີ້']);
    exit;
}

// ລຶບ admin
$stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='admin'");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['status'=>'success', 'message'=>'ລຶບແອັດມິນສໍາເລັດ']);
} else {
    echo json_encode(['status'=>'error', 'message'=>'ເກີດຂໍ້ຜິດພາດໃນການລຶບ']);
}
$stmt->close();
?>
