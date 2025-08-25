<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

// ສ້າງ CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ດຶງຂໍ້ມູນແອັດມິນທັງຫມົດ
$sql = "SELECT * FROM users WHERE role='admin' ORDER BY id DESC";
$result = $conn->query($sql);

include '../includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<h2 style="color:#b8860b; margin-bottom:20px;">👑 ຈັດການບັນຊີແອັດມິນ</h2>

<a href="add.php" class="btn btn-success mb-3" style="background-color:#b8860b; border-color:#a87500;">➕ ເພີ່ມແອັດມິນ</a>

<table class="table table-striped table-hover" style="background: #fff8e1; color: #5c3a00; border: 1px solid #d4af37;">
    <thead style="background-color: #d4af37; color: #fff;">
        <tr>
            <th>ID</th>
            <th>ຊື່ຜູ້ໃຊ້</th>
            <th>ອີເມວ</th>
            <th>ວັນທີສ້າງ</th>
            <th>ຈັດການ</th>
        </tr>
    </thead>
    <tbody id="admin-table-body">
<?php $i = 1; ?>
<?php while($row = $result->fetch_assoc()): ?>
    <tr id="admin-row-<?= $row['id'] ?>">
        <td><?= $i ?></td> <!-- แสดงลำดับแทน ID จริง -->
        <td><?= htmlspecialchars($row['username'], ENT_QUOTES) ?></td>
        <td><?= htmlspecialchars($row['email'], ENT_QUOTES) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" style="background-color:#d4af37; border-color:#b8860b;">✏ ແກ້ໄຂ</a>
            <button class="btn btn-danger btn-sm delete-btn" 
                    data-id="<?= $row['id'] ?>" 
                    data-username="<?= htmlspecialchars($row['username'], ENT_QUOTES) ?>"
                    style="background-color:#a0522d; border-color:#8b4513;">
                    🗑 ລຶບ
            </button>
        </td>
    </tr>
<?php $i++; endwhile; ?>
</tbody>

</table>

<script>
const csrfToken = "<?= $_SESSION['csrf_token'] ?>";

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const username = this.dataset.username;

        Swal.fire({
            title: `ເຈົ້າແນ່ໃຈບໍ່ ທີ່ຈະລຶບ ${username}?`,
            text: "ຂໍ້ມູນນີ້ຈະຖືກລຶບຖານຖານ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b8860b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ລຶບເລີຍ',
            cancelButtonText: 'ຍົກເລີກ',
            background: '#fff8e1',
            color: '#5c3a00'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${id}&token=${csrfToken}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const row = document.getElementById(`admin-row-${id}`);
                        if (row) row.remove();

                        Swal.fire({
                            icon: 'success',
                            title: 'ສຳເລັດ!',
                            text: data.message,
                            background: '#fff8e1',
                            color: '#5c3a00',
                            confirmButtonColor: '#b8860b'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ຜິດພາດ!',
                            text: data.message,
                            background: '#fff8e1',
                            color: '#5c3a00',
                            confirmButtonColor: '#b8860b'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'ເກີດຂໍ້ຜິດພາດ',
                        text: err,
                        background: '#fff8e1',
                        color: '#5c3a00',
                        confirmButtonColor: '#b8860b'
                    });
                });
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
