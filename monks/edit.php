<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>window.location='list.php';</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM monks WHERE monk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$monk = $result->fetch_assoc();

if (!$monk) {
    echo "<script>window.location='list.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $birth_date = $_POST['birth_date'];
    $village = trim($_POST['village']);
    $district = trim($_POST['district']);
    $province = trim($_POST['province']);
    $ordination_date = $_POST['ordination_date'];
    $temple_id = $_POST['temple_id'];
    $phone = trim($_POST['phone']);
    $type = $_POST['type'];
    $move_in_date = $_POST['move_in_date'];

    $image_path = $monk['image_path'];

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('ຮູບຕ້ອງເປັນ .jpg, .jpeg, .png, .gif ເທົ່ານັ້ນ');</script>";
            exit;
        }

        if ($_FILES['image']['size'] > 50 * 1024 * 1024) {
            echo "<script>alert('ຂະໜາດຮູບບໍ່ເກີນ 50MB');</script>";
            exit;
        }

        if (!empty($monk['image_path']) && file_exists("../uploads/" . $monk['image_path'])) {
            unlink("../uploads/" . $monk['image_path']);
        }

        $image_path = uniqid('monk_', true) . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image_path);
    }

    $stmt = $conn->prepare("UPDATE monks SET 
        first_name=?, last_name=?, birth_date=?, village=?, district=?, province=?, 
        ordination_date=?, temple_id=?, phone=?, type=?, move_in_date=?, image_path=? 
        WHERE monk_id=?");
    $stmt->bind_param("ssssssssssssi",
        $first_name, $last_name, $birth_date, $village, $district, $province,
        $ordination_date, $temple_id, $phone, $type, $move_in_date, $image_path, $id);

    if ($stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'ສຳເລັດ!',
                text: 'ອັບເດດຂໍ້ມູນພຣະແລ້ວ',
                showConfirmButton: false,
                timer: 1800
            }).then(() => window.location = 'list.php');
        </script>";
    } else {
        echo "<div class='alert alert-danger'>ຜິດພາດ: " . $stmt->error . "</div>";
    }
}
?>

<!-- ✅ SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ Style -->
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
    transition: 0.3s ease;
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
    transition: 0.3s ease;
  }
  .btn-gold:hover {
    background-color: #a0821c;
    transform: translateY(-2px);
  }
  #previewEdit, #currentImage {
    width: 120px;
    height: auto;
    border-radius: 10px;
    border: 2px solid #d4af37;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
</style>

<div class="container mt-5 mb-5">
  <h3 class="mb-4 text-gold"><i class="bi bi-pencil-square me-2"></i>ແກ້ໄຂຂໍ້ມູນພຣະ</h3>
  <div class="form-card">
    <form method="POST" enctype="multipart/form-data" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ຊື່</label>
        <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($monk['first_name']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">ນາມສະກຸນ</label>
        <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($monk['last_name']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນເກີດ</label>
        <input type="date" name="birth_date" class="form-control" value="<?= $monk['birth_date'] ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ບ້ານ</label>
        <input type="text" name="village" class="form-control" value="<?= htmlspecialchars($monk['village']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ເມືອງ</label>
        <input type="text" name="district" class="form-control" value="<?= htmlspecialchars($monk['district']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ແຂວງ</label>
        <input type="text" name="province" class="form-control" value="<?= htmlspecialchars($monk['province']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນບວດ</label>
        <input type="date" name="ordination_date" class="form-control" value="<?= $monk['ordination_date'] ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັດສັງກັດ</label>
        <select name="temple_id" class="form-select" required>
          <option value="">-- ເລືອກວັດ --</option>
          <?php
            $temples = $conn->query("SELECT * FROM temples ORDER BY temple_name ASC");
            while ($row = $temples->fetch_assoc()):
              $selected = $monk['temple_id'] == $row['temple_id'] ? 'selected' : '';
          ?>
            <option value="<?= $row['temple_id'] ?>" <?= $selected ?>>
              <?= htmlspecialchars($row['temple_name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">ເບີໂທ</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($monk['phone']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ປະເພດ</label>
        <select name="type" class="form-select">
          <option value="ພຣະ" <?= $monk['type'] == 'ພຣະ' ? 'selected' : '' ?>>ພຣະ</option>
          <option value="ສາມະເນນ" <?= $monk['type'] == 'ສາມະເນນ' ? 'selected' : '' ?>>ສາມະເນນ</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">ວັນຍ້າຍເຂົ້າ</label>
        <input type="date" name="move_in_date" class="form-control" value="<?= $monk['move_in_date'] ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">ຮູບປັດຈຸບັນ</label><br>
        <img id="currentImage" src="../uploads/<?= htmlspecialchars($monk['image_path']) ?>" alt="monk" class="mb-2">
      </div>
      <div class="col-md-4">
        <label class="form-label">ຮູບໃໝ່ (ຖ້າຈະປ່ຽນ)</label>
        <input type="file" name="image" accept="image/*" class="form-control" onchange="previewImageEdit(event)">
        <img id="previewEdit" src="#" style="display:none;" alt="preview">
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-gold"><i class="bi bi-save me-1"></i> ບັນທຶກການແກ້ໄຂ</button>
        <a href="list.php" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left-circle me-1"></i> ກັບຄືນ</a>
      </div>
    </form>
  </div>
</div>

<script>
function previewImageEdit(event) {
  const reader = new FileReader();
  reader.onload = function() {
    const preview = document.getElementById('previewEdit');
    preview.src = reader.result;
    preview.style.display = 'block';
  }
  reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php include '../includes/footer.php'; ?>
