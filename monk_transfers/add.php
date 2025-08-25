<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monk_id = $_POST['monk_id'];
    $temple_id = $_POST['temple_id'];
    $transfer_type = $_POST['transfer_type'];
    $transfer_date = $_POST['transfer_date'];
    $goto_temple = $_POST['goto_temple'];
    $reason = $_POST['reason'];

    // 🔍 ตรวจสอบการย้ายล่าสุดของพระ
    $stmtCheck = $conn->prepare("SELECT transfer_type FROM monk_transfers WHERE monk_id = ? ORDER BY transfer_date DESC LIMIT 1");
    $stmtCheck->bind_param("i", $monk_id);
    $stmtCheck->execute();
    $stmtCheck->bind_result($last_type);
    $has_data = $stmtCheck->fetch();
    $stmtCheck->close();

    if ($has_data) {
        if ($last_type === 'ຍ້າຍອອກ') {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'ບໍ່ສາມາດເພີ່ມໄດ້',
                    text: 'ພຣະຮູບນີ້ໄດ້ຍ້າຍອອກແລ້ວ',
                    confirmButtonText: 'ຕົກລົງ'
                }).then(() => window.location='list.php');
            </script>";
            exit;
        }

        if ($last_type === 'ຍ້າຍເຂົ້າ' && $transfer_type === 'ຍ້າຍເຂົ້າ') {
            echo "<script>
                Swal.fire({
                    icon: 'info',
                    title: 'ບໍ່ສາມາດເພີ່ມການຍ້າຍເຂົ້າໄດ້',
                    text: 'ພຣະຮູບນີ້ຢູ່ໃນວັດແລ້ວ ກະລຸນາຍ້າຍອອກກ່ອນ',
                    confirmButtonText: 'ຕົກລົງ'
                }).then(() => window.location='list.php');
            </script>";
            exit;
        }
    }

    // ✅ เพิ่มข้อมูลการย้าย
    $stmt = $conn->prepare("INSERT INTO monk_transfers (monk_id, temple_id, transfer_type, transfer_date, goto_temple, reason)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $monk_id, $temple_id, $transfer_type, $transfer_date, $goto_temple, $reason);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'ສຳເລັດ!',
                text: 'ເພີ່ມຂໍ້ມູນການຍ້າຍແລ້ວ',
                timer: 1800,
                showConfirmButton: false
            }).then(() => window.location='list.php');
        </script>";
        exit;
    } else {
        echo "<div class='alert alert-danger mt-3'>ຜິດພາດ: {$stmt->error}</div>";
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
  <h3 class="mb-4"><i class="bi bi-plus-circle me-2"></i>ເພີ່ມຂໍ້ມູນການຍ້າຍ</h3>

  <form method="POST" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">ພຣະສົງ</label>
      <select name="monk_id" class="form-select" required>
        <option value="">-- ເລືອກພຣະ --</option>
        <?php
        $monks = $conn->query("SELECT monk_id, first_name, last_name FROM monks ORDER BY first_name ASC");
        while ($row = $monks->fetch_assoc()):
        ?>
        <option value="<?= $row['monk_id'] ?>"><?= $row['first_name'] . ' ' . $row['last_name'] ?></option>
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
        ?>
        <option value="<?= $row['temple_id'] ?>"><?= $row['temple_name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">ປະເພດການຍ້າຍ</label>
      <select name="transfer_type" class="form-select" required>
        <option value="ຍ້າຍເຂົ້າ">ຍ້າຍເຂົ້າ</option>
        <option value="ຍ້າຍອອກ">ຍ້າຍອອກ</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">ວັນທີຍ້າຍ</label>
      <input type="date" name="transfer_date" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">ວັດປາຍທາງ</label>
      <input type="text" name="goto_temple" class="form-control" placeholder="ຖ້າຍ້າຍອອກ">
    </div>

    <div class="col-12">
      <label class="form-label">ເຫດຜົນ</label>
      <textarea name="reason" rows="2" class="form-control" placeholder="ເຫດຜົນເພີ່ມເຕີມ..."></textarea>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> ບັນທຶກ</button>
      <a href="list.php" class="btn btn-secondary">ກັບຄືນ</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
