<?php 
session_start();
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    echo "<div class='alert alert-danger'>ລະຫັດບໍ່ຖືກຕ້ອງ</div><a href='transfer_select.php'>ກັບຄືນ</a>";
    include '../includes/footer.php';
    exit;
}

$stmt = $conn->prepare("
    SELECT mt.*, m.first_name, m.last_name, m.birth_date,
           t.temple_name, t.village, t.district, t.province,
           d.temple_name AS destination_temple
    FROM monk_transfers mt
    LEFT JOIN monks m ON mt.monk_id = m.monk_id
    LEFT JOIN temples t ON mt.temple_id = t.temple_id
    LEFT JOIN temples d ON mt.goto_temple = d.temple_id
    WHERE mt.transfer_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='alert alert-warning'>ບໍ່ພົບຂໍ້ມູນການຍ້າຍ</div><a href='transfer_select.php'>ກັບຄືນ</a>";
    include '../includes/footer.php';
    exit;
}

$age = date('Y') - date('Y', strtotime($data['birth_date']));
$transferDate = date('d/m/Y', strtotime($data['transfer_date']));
$reportDate = date('d/m/Y');
$documentRef = "REF-" . date('Ymd') . "-" . $id;
?>

<style>
  body {
    font-family: 'Noto Sans Lao', 'Phetsarath OT', sans-serif;
    background: #f8f3e8;
    color: #3e2f14;
    font-size: 16px;
  }
  .document-container {
    background: #fffdfa;
    padding: 3rem 2.5rem;
    box-shadow: 0 0 20px rgba(0,0,0,0.15);
    border-radius: 10px;
    max-width: 850px;
    margin: auto;
    border: 1px solid #e4d9c1;
  }
  .title {
    color: #a67c00;
    font-weight: bold;
    border-bottom: 2px solid #d4af37;
    padding-bottom: 10px;
    margin-bottom: 30px;
    font-size: 1.4rem;
  }
  .document-ref {
    font-size: 0.85rem;
    color: #777;
    text-align: right;
  }
  .bold-text {
    font-weight: bold;
    color: #5b3e0b;
  }
  .signature-area {
    margin-top: 80px;
    display: flex;
    justify-content: space-between;
    font-size: 1rem;
  }
  .signature-area .box {
    text-align: center;
    width: 48%;
  }
  .print-controls {
    text-align: center;
    margin: 2rem auto;
  }
  @media print {
    .no-print {
      display: none !important;
    }
    body {
      background: white;
    }
  }
</style>

<!-- ✅ ปุ่มควบคุม -->
<div class="no-print print-controls">
  <button onclick="window.print()" class="btn btn-success me-2"><i class="bi bi-printer"></i> ພິມ</button>
  <button onclick="exportPDF()" class="btn btn-danger me-2"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
  <a href="transfer_select.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> ກັບ</a>
</div>

<!-- ✅ เนื้อหาใบย้าย -->
<div id="print-section" class="document-container">
  <div class="text-center mb-4">
    <p class="mb-0">ສາທາລະນະລັດ ປະຊາທິປະໄຕ ປະຊາຊົນລາວ</p>
    <p>ສັນຕິພາບ ເອກະລາດ ປະຊາທິປະໄຕ ເອກະພາບ ວັດທະນະຖາວອນ</p>
  </div>

  <div class="mb-3">
    <p><strong>ວັດ:</strong> <?= htmlspecialchars($data['temple_name']) ?></p>
    <p><strong>ທີ່ຢູ່:</strong> ບ້ານ <?= htmlspecialchars($data['village']) ?>, ເມືອງ <?= htmlspecialchars($data['district']) ?>, ແຂວງ <?= htmlspecialchars($data['province']) ?></p>
    <p class="document-ref">ເລກທີ: <?= $documentRef ?> | ວັນທີ: <?= $reportDate ?></p>
  </div>

  <h2 class="title text-center">ໃບຢືນຢັນການຍ້າຍອອກຂອງພຣະ</h2>

  <div>
    <p>
      ຂໍແຈ້ງວ່າ: ພຣະ <span class="bold-text"><?= htmlspecialchars($data['first_name'].' '.$data['last_name']) ?></span>
      ອາຍຸ <?= $age ?> ປີ ສັງກັດຢູ່ວັດ <span class="bold-text"><?= htmlspecialchars($data['temple_name']) ?></span>
      ໄດ້ຮັບການອະນຸມັດໃຫ້ຍ້າຍໄປຈຳພັນສາຢູ່ວັດ
      <span class="bold-text"><?= htmlspecialchars($data['destination_temple'] ?? '-') ?></span> ແຕ່ວັນທີ <?= $transferDate ?> ເປັນຕົ້ນໄປ.
    </p>

    <p><strong>ເຫດຜົນ:</strong> <?= nl2br(htmlspecialchars($data['reason'])) ?></p>

    <p>ເອກະສານສະບັບນີ້ອອກໃຫ້ເພື່ອຢືນຢັນການຍ້າຍສັງກັດຢ່າງເປັນທາງການ.</p>

    <?php if (!empty($data['notes'])): ?>
      <p><strong>ໝາຍເຫດ:</strong> <?= nl2br(htmlspecialchars($data['notes'])) ?></p>
    <?php endif; ?>
  </div>

  <!-- ✅ ลายเซ็น -->
  <div class="signature-area">
    <div class="box">
      <p>ຜູ້ຍື່ນຄຳຮ້ອງ</p>
      <div style="margin-top: 60px;">......................................</div>
    </div>
    <div class="box">
      <p>ເຈົ້າອາວາດ</p>
      <div style="margin-top: 60px;">......................................</div>
      <p>(......................................)</p>
    </div>
  </div>
</div>

<!-- ✅ Export PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportPDF() {
  const element = document.getElementById('print-section');
  const opt = {
    margin: 0.5,
    filename: 'ໃບຍົກຍ້າຍ_<?= htmlspecialchars($data['last_name']) ?>.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
}
</script>

<?php include '../includes/footer.php'; ?>
