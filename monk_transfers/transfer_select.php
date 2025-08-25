<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$stmt = $conn->prepare("
  SELECT mt.transfer_id, m.first_name, m.last_name, mt.transfer_date
  FROM monk_transfers mt
  LEFT JOIN monks m ON mt.monk_id = m.monk_id
  WHERE mt.transfer_type = 'ຍ້າຍອອກ'
  ORDER BY mt.transfer_date DESC
");
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
?>

<style>
  body {
    background-color: #fbf8f3;
    font-family: 'Noto Sans Lao', sans-serif;
    color: #5e3b1e;
  }
  .card {
    background-color: #fffdf6;
    border: 1px solid #e0cda6;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    border-radius: 1rem;
  }
  .btn-primary {
    background-color: #c59d28;
    border-color: #c59d28;
  }
  .btn-primary:hover {
    background-color: #a0791c;
    border-color: #a0791c;
  }
  .btn-secondary {
    background-color: #a1887f;
    border-color: #a1887f;
  }
  .btn-secondary:hover {
    background-color: #8d6e63;
    border-color: #8d6e63;
  }
  .section-title {
    color: #8b5e1a;
    font-weight: bold;
    font-size: 1.4rem;
  }
</style>

<div class="container py-5">
  <div class="card p-4">
    <h4 class="section-title text-center mb-4">
      📄 ເລືອກພຣະທີ່ຍ້າຍອອກເພື່ອ Export ໃບຢືນຢັນ
    </h4>

    <?php if (empty($records)): ?>
      <div class="alert alert-warning text-center">ຍັງບໍ່ມີຂໍ້ມູນການຍ້າຍອອກ</div>
    <?php else: ?>
    <form action="transfer_single_export.php" method="get" class="row g-3 justify-content-center">
      <div class="col-md-8">
        <select name="id" class="form-select form-select-lg" required autofocus>
          <option value="">-- ເລືອກຊື່ພຣະ --</option>
          <?php foreach ($records as $rec): ?>
            <option value="<?= $rec['transfer_id'] ?>">
              <?= htmlspecialchars($rec['first_name'] . ' ' . $rec['last_name']) ?> | ຍ້າຍວັນທີ <?= date('d/m/Y', strtotime($rec['transfer_date'])) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4 text-center">
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-file-earmark-arrow-down me-1"></i> Export PDF
        </button>
        <a href="list.php" class="btn btn-secondary ms-2">
          <i class="bi bi-arrow-left-circle me-1"></i> ກັບ
        </a>
      </div>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
