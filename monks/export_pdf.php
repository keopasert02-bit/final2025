<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

// ดึงข้อมูลพระสงฆ์
$stmt = $conn->query("SELECT m.*, t.temple_name,
    TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) as age,
    TIMESTAMPDIFF(YEAR, m.ordination_date, CURDATE()) as monk_year
    FROM monks m
    LEFT JOIN temples t ON m.temple_id = t.temple_id
    ORDER BY m.monk_id DESC");
$monks = $stmt->fetch_all(MYSQLI_ASSOC);
$total = count($monks);

// ดึงชื่อวัดด้วย prepare (ป้องกัน SQL Injection)
$templeStmt = $conn->prepare("SELECT temple_name FROM temples WHERE temple_id = ?");
$templeId = 1;
$templeStmt->bind_param("i", $templeId);
$templeStmt->execute();
$temple = $templeStmt->get_result()->fetch_assoc();
$templeName = $temple['temple_name'] ?? 'ວັດສະພັງໝໍ້ ໄຊຍະຣາມ';

// สถิติประเภทพระ
$monkTypes = [];
foreach ($monks as $monk) {
    $type = $monk['type'] ?: 'ບໍ່ລະບຸ';
    $monkTypes[$type] = ($monkTypes[$type] ?? 0) + 1;
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
  <meta charset="UTF-8">
  <title>ລາຍງານຂໍ້ມູນພຣະ - <?= htmlspecialchars($templeName) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Noto Sans Lao', sans-serif;
      background-color: #fffdf5;
    }
    .temple-logo {
      width: 80px;
      border-radius: 50%;
      border: 3px solid gold;
    }
    .btn-print, .btn-export {
      margin-right: 6px;
    }
    .table th {
      background: linear-gradient(45deg, #c59d28, #b8860b);
      color: white;
      text-align: center;
    }
    .table td {
      font-size: 14px;
    }
    h4, h5 {
      color: #7a5c20;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <div>
      <button class="btn btn-sm btn-outline-primary btn-print" onclick="window.print()">
        <i class="fa fa-print"></i> ພິມ
      </button>
      <button class="btn btn-sm btn-outline-danger btn-export" onclick="exportPDF()">
        <i class="fa fa-file-pdf"></i> PDF
      </button>
      <a href="list.php" class="btn btn-sm btn-secondary">
        <i class="fa fa-arrow-left"></i> ກັບຄືນ
      </a>
    </div>
  </div>

  <div id="print-section">
    <div class="text-center mb-4">
      <img src="../uploads/tammajak.png" alt="Logo" class="temple-logo mb-2"
           onerror="this.onerror=null;this.src='../uploads/default-monk.png';">
      <h4 class="fw-bold"><?= htmlspecialchars($templeName) ?></h4>
      <h5 class="fw-semibold">ລາຍງານຂໍ້ມູນພຣະສົງ ປະຈຳປີ <?= date('Y') ?></h5>
      <p class="text-muted">ວັນທີລາຍງານ: <?= date('d/m/Y') ?></p>
    </div>

    <div class="mb-3">
      <strong>ຈຳນວນລວມ:</strong> <?= $total ?> ຮູບ<br>
      <?php foreach ($monkTypes as $type => $count): ?>
        <strong><?= htmlspecialchars($type) ?>:</strong> <?= $count ?> ຮູບ<br>
      <?php endforeach; ?>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>ຊື່</th>
            <th>ນາມສະກຸນ</th>
            <th>ວັນເກີດ</th>
            <th>ອາຍຸ</th>
            <th>ປະເພດ</th>
            <th>ວັນບວດ</th>
            <th>ບ້ານ</th>
            <th>ເມືອງ</th>
            <th>ແຂວງ</th>
            <th>ວັດສັງກັດ</th>
            <th>ເບີໂທ</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($total > 0): ?>
          <?php $i = 1; foreach ($monks as $m): ?>
          <tr>
            <td class="text-center"><?= $i++ ?></td>
            <td><?= htmlspecialchars($m['first_name']) ?></td>
            <td><?= htmlspecialchars($m['last_name']) ?></td>
            <td><?= $m['birth_date'] ? htmlspecialchars($m['birth_date']) : '-' ?></td>
            <td class="text-center"><?= $m['age'] ?> ປີ</td>
            <td><?= htmlspecialchars($m['type']) ?></td>
            <td><?= $m['ordination_date'] ? htmlspecialchars($m['ordination_date']) : '-' ?></td>
            <td><?= htmlspecialchars($m['village']) ?></td>
            <td><?= htmlspecialchars($m['district']) ?></td>
            <td><?= htmlspecialchars($m['province']) ?></td>
            <td><?= htmlspecialchars($m['temple_name']) ?></td>
            <td><?= htmlspecialchars($m['phone']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="12" class="text-center text-muted">ບໍ່ມີຂໍ້ມູນພຣະສົງ</td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-5 text-end">
      <p>ລົງຊື່: ...............................................</p>
    </div>
  </div>
</div>

<script>
function exportPDF() {
  const element = document.getElementById('print-section');
  html2pdf().set({
    margin: 0.3,
    filename: 'monk_report_' + new Date().toISOString().split('T')[0] + '.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
  }).from(element).save();
}
</script>
</body>
</html>
