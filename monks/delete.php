<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    showAlert('error', 'ID ບໍ່ຖືກຕ້ອງ', 'ບໍ່ພົບຂໍ້ມູນທີ່ຈະລຶບ', 'list.php');
    exit;
}

// ตรวจสอบความสัมพันธ์กับ monk_transfers
$check = $conn->prepare("SELECT COUNT(*) as total FROM monk_transfers WHERE monk_id = ?");
$check->bind_param("i", $id);
$check->execute();
$checkResult = $check->get_result()->fetch_assoc();
$check->close();

if ($checkResult['total'] > 0) {
    showAlert('warning', 'ບໍ່ສາມາດລຶບໄດ້', 'ຂໍ້ມູນພຣະນີ້ຖືກອ້າງອີງໃນການຍ້າຍ', 'list.php', true);
    exit;
}

// ดึงข้อมูล monk
$stmt = $conn->prepare("SELECT image_path FROM monks WHERE monk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$monk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$monk) {
    showAlert('error', 'ບໍ່ພົບຂໍ້ມູນ', 'ພຣະນີ້ບໍ່ມີຢູ່ໃນລະບົບ', 'list.php');
    exit;
}

// ลบไฟล์รูปถ้ามี
if (!empty($monk['image_path']) && file_exists("../uploads/" . $monk['image_path'])) {
    unlink("../uploads/" . $monk['image_path']);
}

// ลบข้อมูล monk
$delete = $conn->prepare("DELETE FROM monks WHERE monk_id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    showAlert('success', 'ສຳເລັດ!', 'ລຶບຂໍ້ມູນພຣະແລ້ວ', 'list.php', false, true);
} else {
    showAlert('error', 'ລຶບບໍ່ສຳເລັດ', 'ກະລຸນາລອງໃໝ່', 'list.php', true);
}
$delete->close();


// 🔔 ฟังก์ชัน SweetAlert2 + Noto Sans Lao
function showAlert($icon, $title, $text, $redirect, $playError = false, $playSuccess = false) {
    $sound = '';
    if ($playError) {
        $sound = "const audio = new Audio('https://cdn.pixabay.com/download/audio/2022/03/15/audio_d6dc9a7de2.mp3'); audio.play();";
    } elseif ($playSuccess) {
        $sound = "const audio = new Audio('https://cdn.pixabay.com/download/audio/2021/08/09/audio_f9f2648441.mp3'); audio.play();";
    }

    // คุมค่าให้ JS รับเป็น boolean จริง ๆ
    $timer = $playSuccess ? "1800" : "null";
    $showConfirm = $playSuccess ? "false" : "true";

    echo <<<HTML
<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>$title</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Noto Sans Lao', sans-serif !important;
    }
    .swal2-popup {
      font-size: 1.1rem !important;
    }
  </style>
</head>
<body>
  <script>
    Swal.fire({
      icon: '$icon',
      title: '$title',
      text: '$text',
      timer: $timer,
      showConfirmButton: $showConfirm
    }).then(() => window.location = '$redirect');
    $sound
  </script>
</body>
</html>
HTML;
}
?>
