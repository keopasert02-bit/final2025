<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: list.php");
    exit;
}

include '../includes/header.php';

$stmt = $conn->prepare("SELECT m.*, t.temple_name, 
                        TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) as age,
                        TIMESTAMPDIFF(YEAR, m.ordination_date, CURDATE()) as ordination_years
                        FROM monks m 
                        LEFT JOIN temples t ON m.temple_id = t.temple_id
                        WHERE m.monk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$monk = $result->fetch_assoc();
$stmt->close();

if (!$monk) {
    header("Location: list.php");
    exit;
}

$imagePath = !empty($monk['image_path']) && file_exists("../uploads/{$monk['image_path']}")
             ? "../uploads/" . htmlspecialchars($monk['image_path'])
             : "../uploads/default-monk.png";
?>

<style>
    body {
        background: linear-gradient(to bottom, #fdf6ec, #fff);
        font-family: 'Noto Sans Lao', sans-serif;
    }
    .monk-card {
        background: #fffef9;
        border: 1px solid #d4af37;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .profile-img {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #d4af37;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    .monk-name {
        font-size: 1.8rem;
        font-weight: bold;
        color: #7a5c20;
    }
    .monk-type {
        font-size: 1rem;
        color: #8b5e1a;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #7a5c20;
        border-left: 5px solid #d4af37;
        padding-left: 10px;
        margin-top: 25px;
        margin-bottom: 15px;
    }
    .info-item {
        margin-bottom: 10px;
    }
    .info-item strong {
        display: inline-block;
        min-width: 140px;
        color: #5e4311;
    }
    .btn-wat {
        background-color: #c59d28;
        color: #fff;
    }
    .btn-wat:hover {
        background-color: #a0761c;
        color: #fff;
    }
</style>

<div class="container my-5">
    <div class="monk-card mx-auto" style="max-width: 700px;">
        <div class="text-center mb-4">
            <img src="<?= $imagePath ?>" class="profile-img mb-3" alt="ຮູບພະສົງ">
            <div class="monk-name"><?= htmlspecialchars($monk['first_name']) ?> <?= htmlspecialchars($monk['last_name']) ?></div>
            <div class="monk-type"><?= htmlspecialchars($monk['type']) ?></div>
        </div>

        <div class="section-title"><i class="fas fa-user"></i> ຂໍ້ມູນສ່ວນຕົວ</div>
        <div class="info-item"><strong>ວັນເກີດ:</strong> <?= date('d/m/Y', strtotime($monk['birth_date'])) ?></div>
        <div class="info-item"><strong>ເບີໂທ:</strong> <?= htmlspecialchars($monk['phone'] ?? '-') ?></div>
        <div class="info-item"><strong>ອາຍຸ:</strong> <?= $monk['age'] ?> ປີ</div>

        <div class="section-title"><i class="fas fa-pray"></i> ຂໍ້ມູນການບວດ</div>
        <div class="info-item"><strong>ວັນບວດ:</strong> <?= date('d/m/Y', strtotime($monk['ordination_date'])) ?></div>
        <div class="info-item"><strong>ພັນສາ:</strong> <?= $monk['ordination_years'] ?> ພັນສາ</div>
        <div class="info-item"><strong>ວັດສັງກັດ:</strong> <?= htmlspecialchars($monk['temple_name'] ?? '-') ?></div>
        <div class="info-item"><strong>ວັນທີຍ້າຍເຂົ້າ:</strong> <?= date('d/m/Y', strtotime($monk['move_in_date'])) ?></div>
        <div class="section-title"><i class="fas fa-map-marker-alt"></i> ທີ່ຢູ່</div>
        <div class="info-item">
            <strong>ບ້ານ/ເມືອງ/ແຂວງ:</strong>
            <?= htmlspecialchars(implode(', ', array_filter([$monk['village'], $monk['district'], $monk['province']]))) ?>
        </div>

        <?php if (!empty($monk['personal_info'])): ?>
        <div class="section-title"><i class="fas fa-book-open"></i> ຂໍ້ມູນເພີ່ມເຕີມ</div>
        <div class="p-3 bg-light border rounded"><?= nl2br(htmlspecialchars($monk['personal_info'])) ?></div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ກັບໄປ</a>
            <a href="edit.php?id=<?= $id ?>" class="btn btn-wat"><i class="fas fa-edit"></i> ແກ້ໄຂ</a>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a2e0fc6622.js" crossorigin="anonymous"></script>

<?php include '../includes/footer.php'; ?>
