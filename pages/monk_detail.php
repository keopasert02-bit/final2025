<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();     // ตรวจสอบการล็อกอิน
checkUser();      // เฉพาะผู้ใช้ทั่วไปเท่านั้น
include 'header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<script>
        alert('ບໍ່ພົບຂໍ້ມູນ');
        window.location = 'member.php';
    </script>";
    exit;
}

$stmt = $conn->prepare("SELECT m.*, t.temple_name,
    TIMESTAMPDIFF(YEAR, m.birth_date, CURDATE()) AS age,
    TIMESTAMPDIFF(YEAR, m.ordination_date, CURDATE()) AS ordination_years
    FROM monks m
    LEFT JOIN temples t ON m.temple_id = t.temple_id
    WHERE m.monk_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$monk = $result->fetch_assoc();
$stmt->close();

if (!$monk) {
    echo "<script>
        alert('ບໍ່ພົບຂໍ້ມູນ');
        window.location = 'member.php';
    </script>";
    exit;
}

$image = !empty($monk['image_path']) && file_exists("../uploads/{$monk['image_path']}")
    ? "../uploads/{$monk['image_path']}"
    : 'https://via.placeholder.com/150x200?text=No+Image';
?>

<style>
.monk-profile {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin: 30px auto;
    max-width: 800px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.08);
}
.monk-profile h2 {
    font-weight: 700;
    font-size: 2rem;
    text-align: center;
    margin-bottom: 20px;
    display: inline-block;
    
    background: linear-gradient(45deg, #B8860B, #DAA520, #FFD700);
    background-clip: text;
    -webkit-background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
    
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
}
.profile-image {
    width: 150px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #ccc;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.profile-table td {
    padding: 10px 15px;
    font-size: 1rem;
}
.profile-table td.label {
    font-weight: bold;
    color: #7a5c20;
    width: 150px;
}
.back-btn {
    margin-top: 30px;
    text-align: center;
}
.logout-btn {
    text-align: end;
    margin-bottom: 10px;
}
</style>

<div class="container monk-profile">
    

    <h2><i class="fas fa-user"></i> ລາຍລະອຽດຂອງພຣະ</h2>
    <div class="row align-items-center">
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="<?= $image ?>" class="profile-image">
        </div>
        <div class="col-md-8">
            <table class="table profile-table">
                <tr><td class="label">ຊື່</td><td><?= htmlspecialchars($monk['first_name']) ?></td></tr>
                <tr><td class="label">ນາມສະກຸນ</td><td><?= htmlspecialchars($monk['last_name']) ?></td></tr>
                <tr><td class="label">ສະຖານະ</td><td><?= htmlspecialchars($monk['type']) ?></td></tr>
                <tr><td class="label">ວັນເກີດ</td><td><?= date("d/m/Y", strtotime($monk['birth_date'])) ?> (<?= $monk['age'] ?> ປີ)</td></tr>
                <tr><td class="label">ວັນບວດ</td><td><?= date("d/m/Y", strtotime($monk['ordination_date'])) ?> (<?= $monk['ordination_years'] ?> ພັນສາ)</td></tr>
                <tr><td class="label">ບ້ານ</td><td><?= $monk['village'] ?>, ເມືອງ <?= $monk['district'] ?>, ແຂວງ <?= $monk['province'] ?></td></tr>
                <tr><td class="label">ວັດສັງກັດ</td><td><?= $monk['temple_name'] ?? '-' ?></td></tr>
                <tr><td class="label">ເບີໂທ</td><td><?= $monk['phone'] ?></td></tr>
                <tr><td class="label">ວັນຍ້າຍເຂົ້າ</td><td><?= date("d/m/Y", strtotime($monk['move_in_date'])) ?></td></tr>
            </table>
        </div>
    </div>
    <div class="back-btn">
        <a href="member.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> ກັບໄປ</a>
    </div>
</div>

<?php include 'footer.php'; ?>
