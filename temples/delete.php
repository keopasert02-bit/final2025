<?php 
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>
        Swal.fire('ຜິດພາດ', 'ລະຫັດບໍ່ຖືກຕ້ອງ', 'error')
        .then(() => { window.location = 'list.php'; });
    </script>";
    exit;
}

$id = (int)$_GET['id'];

// 🔍 ตรวจสอบว่ามีวัดอยู่จริง
$check = $conn->prepare("SELECT temple_id FROM temples WHERE temple_id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo "<script>
        Swal.fire('ຜິດພາດ', 'ບໍ່ພົບຂໍ້ມູນວັດ', 'error')
        .then(() => { window.location = 'list.php'; });
    </script>";
    exit;
}
$check->close();


// 🔍 ตรวจสอบว่ามี monks อ้างอิงหรือไม่
$refCheck1 = $conn->prepare("SELECT COUNT(*) FROM monks WHERE temple_id = ?");
$refCheck1->bind_param("i", $id);
$refCheck1->execute();
$refCheck1->bind_result($totalMonks);
$refCheck1->fetch();
$refCheck1->close();

if ($totalMonks > 0) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'ບໍ່ສາມາດລຶບໄດ້',
            text: 'ຍັງມີພະສົງຢູ່ໃນວັດນີ້',
        }).then(() => {
            window.location = 'list.php';
        });
    </script>";
    exit;
}

// 🔍 ตรวจสอบว่ามี monk_transfers อ้างอิงหรือไม่
$refCheck2 = $conn->prepare("SELECT COUNT(*) FROM monk_transfers WHERE temple_id = ?");
$refCheck2->bind_param("i", $id);
$refCheck2->execute();
$refCheck2->bind_result($totalTransfers);
$refCheck2->fetch();
$refCheck2->close();

if ($totalTransfers > 0) {
    echo "<script>
        Swal.fire({
            icon: 'warning',
            title: 'ບໍ່ສາມາດລຶບໄດ້',
            text: 'ມີຂໍ້ມູນການຍ້າຍທີ່ກ່ຽວຂ້ອງກັບວັດນີ້',
        }).then(() => {
            window.location = 'list.php';
        });
    </script>";
    exit;
}

// ✅ ลบวัดได้
$stmt = $conn->prepare("DELETE FROM temples WHERE temple_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'ສໍາເລັດ!',
            text: 'ລຶບວັດອອກຈາກລະບົບແລ້ວ',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location = 'list.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire('Error', 'ລຶບບໍ່ສໍາເລັດ', 'error')
        .then(() => { window.location = 'list.php'; });
    </script>";
}

$stmt->close();
?>
