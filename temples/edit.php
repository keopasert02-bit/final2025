<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire('ຜິດພາດ', 'ລະຫັດບໍ່ຖືກຕ້ອງ', 'error').then(() => {
        window.location = 'list.php';
      });
    </script>";
    exit;
}

// 🔍 ดึงข้อมูลวัด
$stmt = $conn->prepare("SELECT * FROM temples WHERE temple_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$temple = $result->fetch_assoc();
$stmt->close();

if (!$temple) {
    echo "<script>
      Swal.fire('ບໍ່ພົບຂໍ້ມູນ', 'ຂໍ້ມູນບໍ່ມີໃນລະບົບ', 'warning').then(() => {
        window.location = 'list.php';
      });
    </script>";
    exit;
}

// ✅ อัปเดต
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temple_name = $_POST['temple_name'];
    $village = $_POST['village'];
    $district = $_POST['district'];
    $province = $_POST['province'];

    $stmt = $conn->prepare("UPDATE temples SET temple_name=?, village=?, district=?, province=? WHERE temple_id=?");
    $stmt->bind_param("ssssi", $temple_name, $village, $district, $province, $id);

    if ($stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
          Swal.fire({
            icon: 'success',
            title: 'ສໍາເລັດ!',
            text: 'ອັບເດດຂໍ້ມູນວັດແລ້ວ',
            timer: 1800,
            showConfirmButton: false
          }).then(() => {
            window.location = 'list.php';
          });
        </script>";
    } else {
        echo "<script>
          Swal.fire('ຜິດພາດ', 'ບໍ່ສາມາດບັນທຶກໄດ້', 'error');
        </script>";
    }
}
?>

<!-- ✅ Style -->
<style>
  .text-brown {
    color: #7a5c20;
  }
  .form-card {
    background: linear-gradient(to bottom right, #fff8e1, #fff);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .form-label {
    color: #7a5c20;
    font-weight: 500;
  }
  .btn-primary {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-primary:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4 fw-bold text-brown"><i class="bi bi-pencil-square me-2"></i>ແກ້ໄຂຂໍ້ມູນວັດ</h3>

  <form method="POST" class="form-card row g-3">
    <div class="col-md-6">
      <label class="form-label">ຊື່ວັດ *</label>
      <input type="text" name="temple_name" class="form-control shadow-sm" required value="<?= htmlspecialchars($temple['temple_name']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">ບ້ານ</label>
      <input type="text" name="village" class="form-control shadow-sm" value="<?= htmlspecialchars($temple['village']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">ເມືອງ</label>
      <input type="text" name="district" class="form-control shadow-sm" value="<?= htmlspecialchars($temple['district']) ?>">
    </div>
    <div class="col-md-6">
      <label class="form-label">ແຂວງ</label>
      <input type="text" name="province" class="form-control shadow-sm" value="<?= htmlspecialchars($temple['province']) ?>">
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> ບັນທຶກການແກ້ໄຂ
      </button>
      <a href="list.php" class="btn btn-outline-secondary ms-2">
        <i class="bi bi-arrow-left"></i> ກັບຄືນ
      </a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
