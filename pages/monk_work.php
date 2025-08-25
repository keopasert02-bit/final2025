<?php 
session_start();
require_once '../config.php';
require_once '../auth.php';

// ✅ ตรวจสอบว่าล็อกอินแล้ว
checkLogin();    

// ✅ ตรวจสอบว่าเป็นผู้ใช้ทั่วไป (ไม่ใช่ admin)
checkUser();     

include 'header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg, #d4af37, #c59d28);
        color: white;
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .page-title {
        font-size: 2.3rem;
        font-weight: bold;
    }
    .page-subtitle {
        font-size: 1.1rem;
        color: #fffde7;
    }
    .stats-summary {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        margin: 30px 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .stats-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    .stat-item {
        background: #fffde7;
        padding: 15px 25px;
        border-radius: 10px;
        text-align: center;
        min-width: 150px;
    }
    .stat-number {
        font-size: 2rem;
        color: #c59d28;
        font-weight: bold;
    }
    .stat-label {
        color: #555;
    }
    .duty-card {
        background: #ffffff;
        border: 2px solid #f3e5ab;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    .duty-header {
        border-bottom: 1px solid #f3e5ab;
        margin-bottom: 15px;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .duty-icon {
        font-size: 1.8rem;
        color: #c59d28;
    }
    .duty-description {
        font-style: italic;
        color: #777;
    }
    .monks-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    .monk-item {
        background: #fff8e1;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sequence-badge {
        background: #c59d28;
        color: white;
        border-radius: 20px;
        padding: 5px 15px;
        font-weight: bold;
        font-size: 1rem;
    }
    .empty-state {
        text-align: center;
        font-style: italic;
        color: #888;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">ຕາຕະລາງໜ້າທີ່ວຽກຜຽນຂອງພຣະສົງ</h1>
        <p class="page-subtitle">ຕາຕະລາງງານ ແລະ ການມອບໝາຍໜ້າທີ່ຂອງພຣະສົງ</p>
    </div>

    <?php
    $total_works = $conn->query("SELECT COUNT(*) as count FROM monk_work")->fetch_assoc()['count'] ?? 0;
    $total_assignments = $conn->query("SELECT COUNT(*) as count FROM monk_work_members")->fetch_assoc()['count'] ?? 0;
    $active_monks = $conn->query("SELECT COUNT(DISTINCT monk_id) as count FROM monk_work_members")->fetch_assoc()['count'] ?? 0;
    ?>

    <div class="stats-summary">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?= $total_works ?></div>
                <div class="stat-label">ຈຳນວນວຽກ</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $total_assignments ?></div>
                <div class="stat-label">ການມອບໝາຍ</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $active_monks ?></div>
                <div class="stat-label">ຈຳນວນພຣະ</div>
            </div>
        </div>
    </div>

    <div class="duties-container">
        <?php
        $sql = "SELECT work_id, work_name, work_description FROM monk_work ORDER BY work_order ASC";
        $works = $conn->query($sql);

        $workIcons = ['fas fa-broom', 'fas fa-praying-hands', 'fas fa-utensils', 'fas fa-leaf', 'fas fa-water', 'fas fa-bell'];
        $iconIndex = 0;

        if ($works && $works->num_rows > 0):
            while ($work = $works->fetch_assoc()):
                $currentIcon = $workIcons[$iconIndex % count($workIcons)];
                $iconIndex++;
        ?>
            <div class="duty-card">
                <div class="duty-header">
                    <i class="<?= $currentIcon ?> duty-icon"></i>
                    <div>
                        <h3><?= htmlspecialchars($work['work_name']) ?></h3>
                        <?php if (!empty($work['work_description'])): ?>
                            <div class="duty-description"><?= nl2br(htmlspecialchars($work['work_description'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
                $stmt = $conn->prepare("
                    SELECT m.first_name, m.last_name, m.type, mwm.sequence 
                    FROM monk_work_members mwm 
                    JOIN monks m ON mwm.monk_id = m.monk_id 
                    WHERE mwm.work_id = ? 
                    ORDER BY mwm.sequence ASC
                ");
                $stmt->bind_param("i", $work['work_id']);
                $stmt->execute();
                $members = $stmt->get_result();
                ?>

                <ul class="monks-list">
                    <?php if ($members->num_rows > 0): ?>
                        <?php while ($monk = $members->fetch_assoc()): ?>
                            <?php
                            $typeLabel = $monk['type'] === 'ພຣະ' ? 'ພຣະ' : ($monk['type'] === 'ສາມະເນນ' ? 'ສາມະເນນ' : 'ອື່ນໆ');
                            ?>
                            <li class="monk-item">
                                <div class="sequence-badge">ຫນ່ວຍ <?= (int)$monk['sequence'] ?></div>
                                <div class="monk-name"><?= $typeLabel ?> <?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?></div>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li class="empty-state">ບໍ່ມີການມອບໝາຍ</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endwhile; else: ?>
            <div class="duty-card empty-state">ຍັງບໍ່ມີວຽກທີ່ຖືກກຳນົດ</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
