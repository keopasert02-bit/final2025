<?php
session_start();
require_once '../config.php';
include 'header.php';

$typeFilter = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM monks WHERE 1";
$params = [];

if (!empty($typeFilter)) {
    $sql .= " AND type = ?";
    $params[] = $typeFilter;
}
if (!empty($search)) {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// แสดงພຣະก่อนສາມະເນນ
$sql .= " ORDER BY 
  CASE 
    WHEN type = 'ພຣະ' THEN 0
    WHEN type = 'ສາມະເນນ' THEN 1
    ELSE 2
  END,
  first_name ASC";

$stmt = $conn->prepare($sql);

if (count($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$totalMonks = $result->num_rows;
$isLoggedIn = isset($_SESSION['user_id']); // ตรวจสอบว่ามีการ login อยู่ไหม
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body {
        background: linear-gradient(135deg, #fafafa 0%, #f8f9fa 100%);
        min-height: 100vh;
    }

    .page-header {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.08);
        text-align: center;
    }

    .page-title {
        background: linear-gradient(45deg, #B8860B, #DAA520, #FFD700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 1rem;
    }

    .page-subtitle {
        color: #666;
        font-size: 1.1rem;
        margin: 0;
    }

    .stats-card {
        background: linear-gradient(135deg, white 0%, rgba(255, 215, 0, 0.02) 100%);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid rgba(218, 165, 32, 0.2);
        box-shadow: 0 4px 20px rgba(218, 165, 32, 0.1);
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stats-icon {
        background: linear-gradient(135deg, #DAA520 0%, #FFD700 100%);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(218, 165, 32, 0.3);
    }

    .stats-text {
        font-size: 1.2rem;
        color: #333;
        margin: 0;
        font-weight: 500;
    }

    .stats-number {
        color: #B8860B;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .modern-table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.08);
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .modern-table th,
    .modern-table td {
        text-align: center;
        padding: 20px 15px;
        font-size: 0.95rem;
        color: #495057;
        vertical-align: middle;
        border: none;
    }

    .modern-table th {
        font-weight: 600;
        border-bottom: 2px solid #DAA520;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #fafafa 0%, rgba(255, 215, 0, 0.03) 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .row-number {
        background: linear-gradient(135deg, #DAA520 0%, #FFD700 100%);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(218, 165, 32, 0.2);
    }

    .monk-photo {
        width: 80px;
        height: 100px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #f1f3f4;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .monk-photo:hover {
        transform: scale(1.05);
        border-color: #DAA520;
    }

    .years-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.2);
    }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #666;
        font-size: 1.1rem;
    }

    .no-data i {
        font-size: 2.5rem;
        color: #ddd;
        margin-bottom: 15px;
        display: block;
    }

    @media (max-width: 768px) {
        .page-header { padding: 2rem 1.5rem; }
        .page-title { font-size: 1.8rem; }
        .stats-card { flex-direction: column; text-align: center; gap: 15px; }
        .monk-photo { width: 60px; height: 80px; }
        .modern-table th, .modern-table td { padding: 15px 10px; font-size: 0.9rem; }
        .stats-icon { width: 50px; height: 50px; font-size: 1.2rem; }
    }
</style>

<div class="container my-5">
    <div class="page-header">
        <h2 class="page-title"><i class="fas fa-users me-2"></i>ພຣະສົງໃນວັດ</h2>
        <p class="page-subtitle">ຄົ້ນຫາ ຫຼື ກອງຂໍ້ມູນຕາມປະເພດ</p>
    </div>

    <form method="GET" class="row mb-4 g-2">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="ຄົ້ນຫາຊື່/ນາມສະກຸນ" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="type" class="form-select">
                <option value="">-- ທຸກສະຖານະ --</option>
                <option value="ພຣະ" <?= $typeFilter == 'ພຣະ' ? 'selected' : '' ?>>ພຣະ</option>
                <option value="ສາມະເນນ" <?= $typeFilter == 'ສາມະເນນ' ? 'selected' : '' ?>>ສາມະເນນ</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-warning w-100"><i class="fas fa-search me-1"></i>ຄົ້ນຫາ</button>
        </div>
        <div class="col-md-3">
            <a href="member.php" class="btn btn-outline-secondary w-100">ເລີ່ມໃໝ່</a>
        </div>
    </form>

    <div class="modern-table-container">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th>ລຳດັບ</th>
                        <th>ຮູບ</th>
                        <th>ສະຖານະ</th>
                        <th>ຊື່</th>
                        <th>ນາມສະກຸນ</th>
                        <th>ວັນທີບວດ</th>
                        <th>ພັນສາ</th>
                        <th>ລາຍລະອຽດ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($totalMonks > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $ordination = new DateTime($row['ordination_date']);
                            $today = new DateTime();
                            $years = $today->diff($ordination)->y;

                            $image = !empty($row['image_path']) ? '../uploads/' . $row['image_path'] : 'https://via.placeholder.com/80x100/cccccc/666666?text=No+Image';
                            $typeText = match($row['type']) {
                                'ພຣະ' => 'ພຣະ',
                                'ສາມະເນນ' => 'ສາມະເນນ',
                                default => 'ບໍ່ລະບຸ'
                            };

                            // ✅ ถ้ายังไม่ล็อกอิน แสดงปุ่มแจ้งเตือน
                            if ($isLoggedIn) {
    $detailBtn = "<a href='monk_detail.php?id={$row['monk_id']}' class='btn btn-sm btn-outline-primary'>
                    <i class='fas fa-eye'></i> ດູລາຍລະອຽດ
                </a>";
} else {
    $detailBtn = "<button onclick=\"Swal.fire({
                        icon: 'warning',
                        title: 'ກະລຸນາລັອກອິນກ່ອນ',
                        text: 'ເຂົ້າລະບົບເພື່ອເບິ່ງລາຍລະອຽດຂອງພຣະ',
                        confirmButtonText: 'ເຂົ້າລະບົບ',
                        showCancelButton: true,
                        cancelButtonText: 'ຍົກເລີກ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = '../login.php';
                        }
                    });\" 
                    class='btn btn-sm btn-outline-secondary'>
                    <i class='fas fa-lock'></i> ລ໋ອກອິນກ່ອນ
                </button>";
}


                            echo "<tr>
                                <td><div class='row-number'>{$i}</div></td>
                                <td><img src='{$image}' class='monk-photo'></td>
                                <td>{$typeText}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>" . date("d/m/Y", strtotime($row['ordination_date'])) . "</td>
                                <td><span class='years-badge'>{$years} ພັນສາ</span></td>
                                <td>{$detailBtn}</td>
                            </tr>";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='8' class='no-data'><i class='fas fa-users'></i> ບໍ່ພົບຂໍ້ມູນ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
