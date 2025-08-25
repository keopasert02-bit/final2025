<?php 
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$result = $conn->query("SELECT * FROM temple_events ORDER BY event_date DESC");
include '../includes/header.php';
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
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.1);
}
</style>

<div class="container mt-4">
    <div class="card-temple mb-3">
        <h3 class="mb-0">
            <i class="bi bi-calendar-event me-2 text-warning"></i>
            ລາຍການກິດຈະກຳຂອງວັດ
        </h3>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="add_event.php" class="btn btn-temple">
            <i class="bi bi-plus-circle me-1"></i> ເພີ່ມກິດຈະກຳ
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-warning text-center">
                <tr>
                    <th>ຊື່ກິດຈະກຳ</th>
                    <th>ວັນທີ</th>
                    <th>ລາຍລະອຽດ</th>
                    <th width="120">ຈັດການ</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['event_date']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                            <td class="text-center">
                                <a href="edit_event.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="ແກ້ໄຂ">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="delete_event.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete(this)" title="ລົບ">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">ຍັງບໍ່ມີກິດຈະກຳ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(link) {
    Swal.fire({
        title: 'ທ່ານຢືນຢັນຈະລົບກິດຈະກຳນີ້ບໍ?',
        text: "ຂໍ້ມູນນີ້ຈະຖືກລົບຖາວອນ!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'ລົບ',
        cancelButtonText: 'ຍົກເລີກ'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link.href;
        }
    });
    return false;
}
</script>

<?php include '../includes/footer.php'; ?>
