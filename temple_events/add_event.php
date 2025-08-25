<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

$title = $event_date = $description = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $event_date = $_POST['event_date'];
    $description = trim($_POST['description']);

    if (!empty($title) && !empty($event_date)) {
        $stmt = $conn->prepare("INSERT INTO temple_events (title, event_date, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $event_date, $description);
        if ($stmt->execute()) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
            Swal.fire({
                icon: 'success',
                title: 'ເພີ່ມກິດຈະກຳສຳເລັດ!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location = 'list_events.php');
            </script>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>ຜິດພາດ: " . $stmt->error . "</div>";
        }
    } else {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
        Swal.fire({
            icon: 'warning',
            title: 'ກະລຸນາກອກຂໍ້ມູນໃຫ້ຄົບ!',
            confirmButtonText: 'ຕົກລົງ'
        });
        </script>";
    }
}
?>

<style>
.text-brown { color: #8B4513; }
.btn-temple {
    background: linear-gradient(135deg, #d4af37, #ff8c00);
    color: white;
    border: none;
}
.btn-temple:hover {
    background: linear-gradient(135deg, #b8860b, #cd853f);
    color: white;
}
.card-temple {
    background: #fffaf0;
    border-left: 5px solid #d4af37;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.1);
}
</style>

<div class="container mt-4">
    <div class="card-temple mb-4">
        <h3 class="mb-0"><i class="bi bi-calendar-plus me-2 text-warning"></i>ເພີ່ມກິດຈະກຳໃໝ່</h3>
    </div>

    <form method="post">
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-type me-1 text-brown"></i>ຊື່ກິດຈະກຳ</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-calendar-date me-1 text-brown"></i>ວັນທີຈັດ</label>
            <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event_date) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-journal-text me-1 text-brown"></i>ລາຍລະອຽດ</label>
            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-temple">
                <i class="bi bi-save me-1"></i> ບັນທຶກ
            </button>
            <a href="list_events.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle me-1"></i> ກັບຄືນ
            </a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
