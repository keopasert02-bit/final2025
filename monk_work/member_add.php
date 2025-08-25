<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

if (session_status() === PHP_SESSION_NONE) session_start();

// รับ work_id จาก URL
$work_id = isset($_GET['work_id']) ? (int) $_GET['work_id'] : 0;
if ($work_id <= 0) {
    $_SESSION['error'] = 'ລະຫັດວຽກບໍ່ຖືກຕ້ອງ';
    header('Location: work_list.php');
    exit;
}

// ดึงรายชื่อพระทั้งหมด
$monks = $conn->query("SELECT monk_id, first_name, last_name, type FROM monks ORDER BY first_name ASC")->fetch_all(MYSQLI_ASSOC);

// ดึงชื่อวຽກ
$stmt = $conn->prepare("SELECT work_name FROM monk_work WHERE work_id = ?");
$stmt->bind_param("i", $work_id);
$stmt->execute();
$work = $stmt->get_result()->fetch_assoc();
$stmt->close();

// เมื่อมีการบันทึก
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monk_id = (int) $_POST['monk_id'];
    $sequence = (int) $_POST['sequence'];

    // ตรวจสอบซ้ำ
    $check = $conn->prepare("SELECT id FROM monk_work_members WHERE work_id = ? AND monk_id = ?");
    $check->bind_param("ii", $work_id, $monk_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $_SESSION['error'] = 'ພຣະນີ້ຢູ່ໃນວຽກແລ້ວ';
        header("Location: work_detail.php?id=$work_id");
        exit;
    }

    // เพิ่มสมาชิก
    $stmt = $conn->prepare("INSERT INTO monk_work_members (work_id, monk_id, sequence) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $work_id, $monk_id, $sequence);
    if ($stmt->execute()) {
        $_SESSION['success'] = 'ເພີ່ມພຣະໃນວຽກແລ້ວ';
    } else {
        $_SESSION['error'] = 'ບັນທຶກລົ້ມເຫຼວ';
    }
    header("Location: work_detail.php?id=$work_id");
    exit;
}

include '../includes/header.php';
?>

<!-- ✅ STYLE -->
<style>
  .form-card {
    background: linear-gradient(to bottom right, #fffaf0, #fff);
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  .form-label {
    color: #7a5c20;
  }

  .text-brown {
    color: #7a5c20;
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

<!-- ✅ CONTENT -->
<div class="container mt-4 mb-5">
  <h3 class="mb-4 text-brown fw-bold">
    <i class="bi bi-person-plus me-2"></i>
    ເພີ່ມພຣະໃນວຽກ: <?= htmlspecialchars($work['work_name']) ?>
  </h3>

  <!-- ✅ FORM -->
  <form method="post" class="form-card p-4">
    <div class="mb-3">
      <label for="monk_id" class="form-label fw-semibold">ເລືອກພຣະ *</label>
      <select name="monk_id" id="monk_id" class="form-select shadow-sm" required>
        <option value="">-- ເລືອກພຣະ --</option>
        <?php foreach ($monks as $m): ?>
          <?php
            $typeLabel = $m['type'] === 'ພຣະ' ? 'ພຣະ' : ($m['type'] === 'ສາມະເນນ' ? 'ສາມະເນນ' : 'ອື່ນໆ');
          ?>
          <option value="<?= $m['monk_id'] ?>">
            <?= htmlspecialchars($m['first_name'] . ' ' . $m['last_name']) ?> (<?= $typeLabel ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="sequence" class="form-label fw-semibold">ໜ່ວຍ (ລຳດັບ)</label>
      <input type="number" name="sequence" id="sequence" class="form-control shadow-sm" value="1" min="1" required>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn btn-success">
        <i class="bi bi-save me-1"></i> ບັນທຶກ
      </button>
      <a href="work_detail.php?id=<?= $work_id ?>" class="btn btn-outline-secondary ms-2">
        <i class="bi bi-arrow-left"></i> ກັບຄືນ
      </a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
