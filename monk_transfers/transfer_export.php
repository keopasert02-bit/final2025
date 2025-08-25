<?php
require_once '../config.php';        // 🔒 ตั้งค่าการเชื่อมต่อฐานข้อมูล
require_once '../auth.php';          // 🔐 ตรวจสอบการเข้าสู่ระบบ
checkLogin();
checkAdmin();

include '../includes/header.php';    // 📄 Header ของหน้าเว็บ

// ✅ ดึงข้อมูลรายการย้ายพระจากฐานข้อมูล
$stmt = $conn->prepare("
  SELECT mt.*, 
         m.first_name, 
         m.last_name, 
         t.temple_name AS current_temple, 
         d.temple_name AS destination_temple
  FROM monk_transfers mt
  LEFT JOIN monks m ON mt.monk_id = m.monk_id
  LEFT JOIN temples t ON mt.temple_id = t.temple_id
  LEFT JOIN temples d ON mt.goto_temple = d.temple_id
  ORDER BY 
    FIELD(mt.transfer_type, 'ຍ້າຍເຂົ້າ') DESC,  -- ✅ เรียง 'ຍ້າຍເຂົ້າ' ขึ้นก่อน
    mt.transfer_date DESC                          -- ✅ เรียงตามวันที่จากใหม่ไปเก่า
");
$stmt->execute();
$transfers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // ⬅️ เก็บผลลัพธ์เป็น array

// ✅ ดึงข้อมูลวัดที่มี `temple_id = 1` (วัดหลักของระบบ)
$templeStmt = $conn->prepare("
  SELECT temple_name, village, district, province 
  FROM temples 
  WHERE temple_id = 1
");
$templeStmt->execute();
$temple = $templeStmt->get_result()->fetch_assoc();

// ✅ ถ้าไม่มีข้อมูลวัด ให้แสดงข้อความเริ่มต้น
$temple_name = $temple['temple_name'] ?? 'ວັດບໍ່ລະບຸ';
$temple_location = ($temple['village'] ?? '-') . ', ' . 
                   ($temple['district'] ?? '-') . ', ' . 
                   ($temple['province'] ?? '-');
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ລາຍງານການຍ້າຍພຣະ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <style>
    body {
      font-family: 'Noto Sans Lao', sans-serif;
      background-color: #fbf8f3;
    }
    .text-brown { color: #8B4513; }
    .table th {
      background: linear-gradient(135deg, #d4af37, #c59d28);
      color: white;
      text-align: center;
    }
    .table td {
      text-align: center;
      vertical-align: middle;
    }
    .table-striped tbody tr:nth-child(odd) {
      background-color: #f9f5ef;
    }
    .badge {
      font-size: 0.9rem;
      padding: 0.4em 0.7em;
      border-radius: 0.5rem;
    }
    .signature-line {
      margin-top: 80px;
      font-size: 1rem;
      text-align: right;
      padding-right: 60px;
      color: #5d4037;
    }
    .no-print {
      display: flex;
      justify-content: end;
      gap: 10px;
    }
    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="container my-4">
  <div class="no-print mb-3">
    <a href="list.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-1"></i> ກັບຄືນ
    </a>
    <button class="btn btn-danger" onclick="exportPDF()">
      <i class="bi bi-file-earmark-pdf-fill me-1"></i> ສົ່ງອອກ PDF
    </button>
  </div>

  <div id="print-section">
    <div class="text-center mb-4">
      <h3 class="fw-bold text-brown"><?= htmlspecialchars($temple_name) ?></h3>
      <p class="fw-semibold text-secondary"><?= htmlspecialchars($temple_location) ?></p>
      <h4 class="text-brown fw-bold mt-3">ລາຍງານການຍ້າຍພຣະ</h4>
      <p class="text-muted">ວັນທີລາຍງານ: <span id="report-date" class="fw-semibold"></span></p>
    </div>

    <table class="table table-bordered table-striped table-hover">
      <thead>
        <tr>
          <th>ລ/ດ</th>
          <th>ປະເພດ</th>
          <th>ຊື່ພຣະ</th>
          <th>ວັນທີຍ້າຍ</th>
          <th>ປະເພດ</th>
          <th>ຈາກວັດ</th>
          <th>ໄປວັດ</th>
          <th>ເຫດຜົນ</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($transfers) > 0): ?>
          <?php $i = 1; foreach ($transfers as $row): ?>
          <tr>
            <td><?= $i++ ?></td>
            
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= date('d/m/Y', strtotime($row['transfer_date'])) ?></td>
            <td>
              <span class="badge <?= $row['transfer_type'] === 'ຍ້າຍເຂົ້າ' ? 'bg-success' : 'bg-danger' ?>">
                <?= $row['transfer_type'] ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['current_temple'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['goto_temple'] ?? '-') ?></td>
            <td><?= htmlspecialchars($row['reason']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນການຍ້າຍ</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="signature-line">
      ລົງຊື່: .................................................
    </div>
  </div>
</div>

<script>
function formatDateLao(date) {
  return date.toLocaleDateString('lo-LA', {
    year: 'numeric', month: 'long', day: 'numeric',
    timeZone: 'Asia/Vientiane'
  });
}
function formatDateFilename() {
  const d = new Date();
  return d.toISOString().slice(0,10).replace(/-/g, '');
}
function exportPDF() {
  const element = document.getElementById('print-section');
  const opt = {
    margin: 0.5,
    filename: 'ລາຍງານການຍ້າຍພຣະ_' + formatDateFilename() + '.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' },
    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
  };
  html2pdf().set(opt).from(element).save();
}
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('report-date').textContent = formatDateLao(new Date());
});
</script>

<?php include '../includes/footer.php'; ?>
