<?php
require_once 'config.php';
require_once 'auth.php';
checkLogin();
checkAdmin();
include 'includes/header.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลสถิติพื้นฐาน
$stats = $conn->query("
    SELECT 
        (SELECT COUNT(*) FROM temples) AS temple_count,
        (SELECT COUNT(*) FROM monks) AS monk_count,
        (SELECT COUNT(*) FROM monks WHERE type = 'ພຣະ') AS phra_count,
        (SELECT COUNT(*) FROM monks WHERE type = 'ສາມະເນນ') AS samanen_count
")->fetch_assoc();

// ดึงข้อมูลการย้ายล่าสุด
$recent_transfers = $conn->query("
    SELECT 
        m.monk_id,
        m.first_name, 
        m.last_name, 
        t.temple_name, 
        mt.transfer_date, 
        mt.transfer_type,
        t.temple_id
    FROM monk_transfers mt
    LEFT JOIN monks m ON mt.monk_id = m.monk_id
    LEFT JOIN temples t ON mt.temple_id = t.temple_id
    ORDER BY mt.transfer_date DESC
    LIMIT 10
");

?>

<style>
  .dashboard-card {
    background: linear-gradient(135deg, #8b4513, #d4af37);
    color: white;
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
    height: 100%;
  }

  .dashboard-card:hover {
    transform: translateY(-4px);
  }

  .dashboard-card h5 {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
  }

  .dashboard-card .display-6 {
    font-size: 2.2rem;
    font-weight: 700;
  }

  .section-title {
    font-size: 1.6rem;
    font-weight: bold;
    color: #5d4037;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #d4af37;
    padding-bottom: 0.5rem;
  }

  .card {
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 1.5rem;
  }
  
  .card-header {
    background-color: #fbf3e0;
    border-bottom: 1px solid #e9d8a6;
    padding: 1rem 1.5rem;
    border-radius: 1rem 1rem 0 0 !important;
  }
  
  .table th {
    background-color: #f5c242;
    color: #3e2723;
    text-align: center;
    border: none;
  }

  .table td {
    text-align: center;
    vertical-align: middle;
    border-color: #f0f0f0;
  }
  
  .monk-profile {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .monk-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #e9d8a6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    color: #5d4037;
    font-weight: bold;
    font-size: 0.9rem;
  }
  
  .refresh-btn {
    background-color: #8b4513;
    border-color: #8b4513;
    color: white;
  }
  
  .refresh-btn:hover {
    background-color: #6d3608;
    border-color: #6d3608;
  }
  
  .temple-link {
    color: #8b4513;
    text-decoration: none;
    font-weight: 500;
  }
  
  .temple-link:hover {
    color: #d4af37;
    text-decoration: underline;
  }

  .chart-container {
    position: relative;
    height: 280px;
    width: 100%;
  }
</style>

<!-- Dashboard Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-title mb-0">
        <i class="bi bi-speedometer2 me-2"></i>ແດຊບອດຂໍ້ມູນລະບົບວັດ
    </h2>
    <button class="btn refresh-btn" onclick="refreshDashboard()">
        <i class="bi bi-arrow-clockwise me-1"></i> ອັບເດດຂໍ້ມູນ
    </button>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card">
            <h5><i class="bi bi-bank2 me-2"></i>ຈຳນວນວັດ</h5>
            <div class="display-6" id="temple-count"><?= $stats['temple_count'] ?></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card">
            <h5><i class="bi bi-people-fill me-2"></i>ພຣະທັງໝົດ</h5>
            <div class="display-6" id="monk-count"><?= $stats['monk_count'] ?></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card">
            <h5><i class="bi bi-person-fill me-2"></i>ພຣະ</h5>
            <div class="display-6" id="phra-count"><?= $stats['phra_count'] ?></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card">
            <h5><i class="bi bi-person-lines-fill me-2"></i>ສາມະເນນ</h5>
            <div class="display-6" id="samanen-count"><?= $stats['samanen_count'] ?></div>
        </div>
    </div>
</div>


<!-- Recent Transfers -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-arrow-repeat me-2"></i>ການຍ້າຍພຣະລ່າສຸດ
        </h5>
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-warning active" onclick="filterTransfers('all')">ທັງໝົດ</button>
            <button type="button" class="btn btn-outline-warning" onclick="filterTransfers('in')">ຍ້າຍເຂົ້າ</button>
            <button type="button" class="btn btn-outline-warning" onclick="filterTransfers('out')">ຍ້າຍອອກ</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ຊື່ພຣະ</th>
                        <th>ວັດ</th>
                        <th>ປະເພດ</th>
                        <th>ວັນທີຍ້າຍ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_transfers->num_rows > 0): ?>
                        <?php while ($row = $recent_transfers->fetch_assoc()): ?>
                            <tr class="transfer-row <?= $row['transfer_type'] === 'ຍ້າຍເຂົ້າ' ? 'transfer-in' : 'transfer-out' ?>">
                                <td>
                                    <div class="monk-profile">
                                        <div class="monk-avatar">
                                            <?= mb_substr($row['first_name'], 0, 1, 'UTF-8') ?>
                                        </div>
                                        <a href="monk_detail.php?id=<?= $row['monk_id'] ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <a href="temple_detail.php?id=<?= $row['temple_id'] ?>" class="temple-link">
                                        <?= htmlspecialchars($row['temple_name']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($row['transfer_type'] === 'ຍ້າຍເຂົ້າ'): ?>
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-down-circle me-1"></i>ຍ້າຍເຂົ້າ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-up-circle me-1"></i>ຍ້າຍອອກ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y', strtotime($row['transfer_date'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">ບໍ່ມີຂໍ້ມູນການຍ້າຍພຣະ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-center bg-light">
        <a href="monk_transfers/list.php" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-list-ul me-1"></i>ເບິ່ງການຍ້າຍທັງໝົດ
        </a>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

<script>


// ฟังก์ชันกรองข้อมูลการย้าย
function filterTransfers(type) {
    const rows = document.querySelectorAll('.transfer-row');
    
    rows.forEach(row => {
        if (type === 'all') {
            row.style.display = '';
        } else if (type === 'in' && row.classList.contains('transfer-in')) {
            row.style.display = '';
        } else if (type === 'out' && row.classList.contains('transfer-out')) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // อัปเดต active button
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('btn-warning', 'active');
        btn.classList.add('btn-outline-warning');
    });
    
    event.target.classList.remove('btn-outline-warning');
    event.target.classList.add('btn-warning', 'active');
}

// ฟังก์ชันรีเฟรชข้อมูล
function refreshDashboard() {
    const refreshBtn = document.querySelector('.refresh-btn');
    const originalText = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i> ກຳລັງອັບເດດ...';
    refreshBtn.disabled = true;
    
    setTimeout(() => {
        // รีโหลดหน้า
        location.reload();
    }, 1000);
}
</script>

<?php include 'includes/footer.php'; ?>