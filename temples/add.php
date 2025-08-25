<?php 
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temple_name = trim($_POST['temple_name']);
    $village     = trim($_POST['village']);
    $district    = trim($_POST['district']);
    $province    = trim($_POST['province']);

    // 🔍 ກວດສອບວ່າມີວັດນີ້ແລ້ວຫຼືບໍ່ (ບໍ່ໃຫ້ສົນໃຈຕົວພິມໃຫຍ່-ນ້ອຍ ແລະ ຊ່ອງວ່າງ)
    $checkStmt = $conn->prepare("
        SELECT temple_id 
        FROM temples 
        WHERE LOWER(TRIM(temple_name)) = LOWER(TRIM(?))
          AND LOWER(TRIM(village)) = LOWER(TRIM(?))
          AND LOWER(TRIM(district)) = LOWER(TRIM(?))
          AND LOWER(TRIM(province)) = LOWER(TRIM(?))
    ");
    $checkStmt->bind_param("ssss", $temple_name, $village, $district, $province);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // ⚠️ ຖ້າມີຂໍ້ມູນຊໍ້າ → ແຈ້ງເຕືອນ + ນຳເຄີເຊີກັບໄປຊ່ອງຊື່ວັດ
        echo "
        <script>
          Swal.fire({
            icon: 'warning',
            title: 'ມີວັດນີ້ແລ້ວ!',
            text: 'ກະລຸນາກວດສອບກ່ອນເພີ່ມ',
            confirmButtonText: 'ຕົກລົງ'
          }).then(() => {
            document.querySelector('[name=\"temple_name\"]').focus();
          });
        </script>";
    } else {
        // ✅ ຖ້າຍັງບໍ່ມີຂໍ້ມູນນີ້ → ເພີ່ມໃໝ່
        $insertStmt = $conn->prepare("
            INSERT INTO temples (temple_name, village, district, province) 
            VALUES (?, ?, ?, ?)
        ");
        $insertStmt->bind_param("ssss", $temple_name, $village, $district, $province);

        if ($insertStmt->execute()) {
            echo "
            <script>
              Swal.fire({
                icon: 'success',
                title: 'ເພີ່ມວັດສຳເລັດ!',
                showConfirmButton: false,
                timer: 1800
              }).then(() => {
                window.location = 'list.php';
              });
            </script>";
        } else {
            echo "<div class='alert alert-danger'>ຜິດພາດ: {$insertStmt->error}</div>";
        }
        $insertStmt->close();
    }
    $checkStmt->close();
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
  .btn-success {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-success:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4 fw-bold text-brown"><i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນວັດ</h3>

  <form method="POST" class="form-card row g-3">
    <div class="col-md-6">
      <label class="form-label">ຊື່ວັດ *</label>
      <input type="text" name="temple_name" class="form-control shadow-sm" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">ບ້ານ</label>
      <input type="text" name="village" class="form-control shadow-sm">
    </div>
    <div class="col-md-6">
      <label class="form-label">ເມືອງ</label>
      <input type="text" name="district" class="form-control shadow-sm">
    </div>
    <div class="col-md-6">
      <label class="form-label">ແຂວງ</label>
      <input type="text" name="province" class="form-control shadow-sm">
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-save me-1"></i> ບັນທຶກ
      </button>
      <a href="list.php" class="btn btn-outline-secondary ms-2">
        <i class="bi bi-arrow-left"></i> ກັບຄືນ
      </a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
