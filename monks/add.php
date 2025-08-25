<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name       = trim($_POST['first_name']);
    $last_name        = trim($_POST['last_name']);
    $birth_date       = $_POST['birth_date'];
    $village          = trim($_POST['village']);
    $district         = trim($_POST['district']);
    $province         = trim($_POST['province']);
    $ordination_date  = $_POST['ordination_date'];
    $temple_id        = $_POST['temple_id'];
    $phone            = trim($_POST['phone']);
    $type             = $_POST['type'];
    $move_in_date     = $_POST['move_in_date'];

    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0777, true);
    }

    $image_name = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($ext, $allowed)) {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ຟາຍຮູບຜິດ', text: 'ຮູບຕ້ອງເປັນ .jpg .jpeg .png .gif' });
            </script>"; exit;
        }
        if ($_FILES['image']['size'] > 50 * 1024 * 1024) {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ຟາຍຂະໜາດໃຫຍ່', text: 'ຮູບຕ້ອງບໍ່ເກີນ 50MB' });
            </script>"; exit;
        }

        $image_name = uniqid('monk_', true) . '.' . $ext;
        $upload_path = '../uploads/' . $image_name;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'ອັບໂຫລດຜິດພາດ', text: 'ບໍ່ສາມາດບັນທຶກຮູບ' });
            </script>"; exit;
        }
    }

    // ✅ ตรวจสอบว่ามีพระชื่อนี้ในวัดนี้แล้วหรือไม่
    $check = $conn->prepare("SELECT 1 FROM monks WHERE first_name = ? AND last_name = ? AND temple_id = ?");
    $check->bind_param("ssi", $first_name, $last_name, $temple_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'ມີພຣະຊື່ນີ້ແລ້ວ!',
                text: 'ກະລຸນາກວດສອບກ່ອນບັນທຶກ',
                confirmButtonText: 'ຕົກລົງ'
            });
        </script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO monks 
            (first_name, last_name, birth_date, village, district, province, ordination_date, temple_id, phone, type, move_in_date, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", 
            $first_name, $last_name, $birth_date, 
            $village, $district, $province, 
            $ordination_date, $temple_id, $phone, 
            $type, $move_in_date, $image_name);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'ສຳເລັດ!',
                    text: 'ເພີ່ມຂໍ້ມູນພຣະສຳເລັດແລ້ວ',
                    showConfirmButton: false,
                    timer: 1800,
                    timerProgressBar: true,
                    backdrop: `rgba(0,0,0,0.3)`
                }).then(() => window.location = 'list.php');
            </script>";
        } else {
            echo "<div class='alert alert-danger'>ຜິດພາດ: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Custom Style -->
<style>
  body {
    background: linear-gradient(to bottom right, #fffbe6, #fefae0);
    font-family: 'Noto Sans Lao', sans-serif;
  }
  .text-gold {
    color: #b08d28;
  }
  .form-card {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #c59d28;
  }
  .form-label {
    font-weight: 600;
    color: #7a5c20;
  }
  .form-control, .form-select {
    border-radius: 10px;
    border: 1px solid #ccc;
    transition: all 0.3s ease;
  }
  .form-control:focus, .form-select:focus {
    border-color: #c59d28;
    box-shadow: 0 0 0 0.2rem rgba(197, 157, 40, 0.2);
  }
  .btn-gold {
    background-color: #c59d28;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease, transform 0.2s ease;
  }
  .btn-gold:hover {
    background-color: #a0821c;
    transform: translateY(-2px);
  }
  .btn-secondary {
    border-radius: 8px;
  }
  #preview {
    display: none;
    width: 120px;
    height: auto;
    margin-top: 10px;
    border-radius: 12px;
    border: 2px solid #d4af37;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
</style>

<!-- ✅ Main Form -->
<div class="container mt-5 mb-5">
  <h3 class="mb-4 text-gold"><i class="bi bi-person-plus-fill me-2"></i>ເພີ່ມຂໍ້ມູນພຣະ</h3>
  <div class="form-card">
    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ຊື່</label>
        <input type="text" name="first_name" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">ນາມສະກຸນ</label>
        <input type="text" name="last_name" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນເກີດ</label>
        <input type="date" name="birth_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ບ້ານ</label>
        <input type="text" name="village" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ເມືອງ</label>
        <input type="text" name="district" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ແຂວງ</label>
        <input type="text" name="province" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນບວດ</label>
        <input type="date" name="ordination_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັດສັງກັດ</label>
        <select name="temple_id" class="form-select" required>
          <option value="">-- ເລືອກວັດ --</option>
          <?php
          $temples = $conn->query("SELECT * FROM temples ORDER BY temple_name ASC");
          while ($row = $temples->fetch_assoc()):
          ?>
            <option value="<?= $row['temple_id'] ?>"><?= htmlspecialchars($row['temple_name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">ເບີໂທ</label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ປະເພດ</label>
        <select name="type" class="form-select">
          <option value="ພຣະ">ພຣະ</option>
          <option value="ສາມະເນນ">ສາມະເນນ</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນຍ້າຍເຂົ້າ</label>
        <input type="date" name="move_in_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">ຮູບພຣະ</label>
        <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImage(event)">
        <img id="preview" src="#">
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-gold">
          <i class="bi bi-save me-1"></i> ເພີ່ມຂໍ້ມູນ
        </button>
        <a href="list.php" class="btn btn-secondary ms-2">
          <i class="bi bi-arrow-left-circle me-1"></i> ກັບຄືນ
        </a>
      </div>
    </form>
  </div>
</div>

<!-- ✅ Preview Image Script -->
<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const preview = document.getElementById('preview');
      preview.src = reader.result;
      preview.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>

<?php include '../includes/footer.php'; ?>
