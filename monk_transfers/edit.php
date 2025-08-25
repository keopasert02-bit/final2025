<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

// ตรวจสอบ ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>window.location='list.php';</script>";
    exit;
}

// ดึงข้อมูลเดิม
$stmt = $conn->prepare("SELECT * FROM monk_transfers WHERE transfer_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transfer = $result->fetch_assoc();

if (!$transfer) {
    echo "<script>window.location='list.php';</script>";
    exit;
}

// แก้ไขข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monk_id = $_POST['monk_id'];
    $temple_id = $_POST['temple_id'];
    $transfer_type = $_POST['transfer_type'];
    $transfer_date = $_POST['transfer_date'];
    $goto_temple = $_POST['goto_temple'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("UPDATE monk_transfers 
        SET monk_id=?, temple_id=?, transfer_type=?, transfer_date=?, goto_temple=?, reason=?
        WHERE transfer_id=?");
    $stmt->bind_param("iissssi", $monk_id, $temple_id, $transfer_type, $transfer_date, $goto_temple, $reason, $id);

    if ($stmt->execute()) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'ສຳເລັດ!',
                text: 'ແກ້ໄຂຂໍ້ມູນແລ້ວ',
                timer: 1800,
                showConfirmButton: false
            }).then(() => window.location='list.php');
        </script>";
        exit;
    }
}
?>

<!-- ✅ Style โทนวัด -->
<style>
  body {
    background-color: #fdf8ef;
  }
  h3 {
    background: linear-gradient(to right, #d4af37, #c59d28);
    padding: 12px 20px;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  }
  .btn-primary {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-primary:hover {
    background-color: #a0821c;
    border-color: #a0821c;
  }
  .form-label {
    font-weight: 500;
    color: #5e4300;
  }
</style>

<!-- ✅ Content -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4"><i class="bi bi-pencil-square me-2"></i>ແກ້ໄຂຂໍ້ມູນການຍ້າຍ</h3>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">ພຣະສົງ</label>
      <select name="monk_id" class="form-select" required>
        <option value="">-- ເລືອກພຣະ --</option>
        <?php
        $monks = $conn->query("SELECT monk_id, first_name, last_name FROM monks ORDER BY first_name ASC");
        while ($row = $monks->fetch_assoc()):
          $selected = $transfer['monk_id'] == $row['monk_id'] ? 'selected' : '';
        ?>
        <option value="<?= $row['monk_id'] ?>" <?= $selected ?>>
          <?= $row['first_name'] . ' ' . $row['last_name'] ?>
        </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">ວັດປັດຈຸບັນ</label>
      <select name="temple_id" class="form-select" required>
        <option value="">-- ເລືອກວັດ --</option>
        <?php
        $temples = $conn->query("SELECT temple_id, temple_name FROM temples ORDER BY temple_name ASC");
        while ($row = $temples->fetch_assoc()):
          $selected = $transfer['temple_id'] == $row['temple_id'] ? 'selected' : '';
        ?>
        <option value="<?= $row['temple_id'] ?>" <?= $selected ?>>
          <?= $row['temple_name'] ?>
        </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">ປະເພດການຍ້າຍ</label>
      <select name="transfer_type" class="form-select" required>
        <option value="ຍ້າຍເຂົ້າ" <?= $transfer['transfer_type'] === 'ຍ້າຍເຂົ້າ' ? 'selected' : '' ?>>ຍ້າຍເຂົ້າ</option>
        <option value="ຍ້າຍອອກ" <?= $transfer['transfer_type'] === 'ຍ້າຍອອກ' ? 'selected' : '' ?>>ຍ້າຍອອກ</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">ວັນທີຍ້າຍ</label>
      <input type="date" name="transfer_date" class="form-control" value="<?= $transfer['transfer_date'] ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">ວັດປາຍທາງ</label>
      <input type="text" name="goto_temple" class="form-control" value="<?= htmlspecialchars($transfer['goto_temple']) ?>">
    </div>

    <div class="col-12">
      <label class="form-label">ເຫດຜົນ</label>
      <textarea name="reason" rows="2" class="form-control"><?= htmlspecialchars($transfer['reason']) ?></textarea>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> ບັນທຶກການແກ້ໄຂ</button>
      <a href="list.php" class="btn btn-secondary">ກັບຄືນ</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
