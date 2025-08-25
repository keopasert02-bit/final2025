<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();
include '../includes/header.php';

// ข้อความแจ้งเตือนจาก GET
$message = $_GET['message'] ?? '';
$message_type = $_GET['type'] ?? 'success';

// ดึงผู้ใช้ทั่วไปทั้งหมด
$sql = "SELECT * FROM users WHERE role='user' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<h2 style="color:#b8860b; margin-bottom:20px;">👤 ຈັດການຜູ້ໃຊ້</h2>

<a href="add.php" class="btn" style="background-color:#b8860b; color:#fff; border-color:#a87500; margin-bottom:10px;">➕ ເພີ່ມຜູ້ໃຊ້</a>

<table class="table table-hover" style="background:#fff8e1; color:#5c3a00; border:1px solid #d4af37;">
<thead style="background-color:#d4af37; color:#fff;">
<tr>
    <th>ລຳດັບ</th>
    <th>ຊື່ຜູ້ໃຊ້</th>
    <th>Email</th>
    <th>ວັນທີສ້າງ</th>
    <th>ຈັດການ</th>
</tr>
</thead>
<tbody>
<?php $i=1; while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $i ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" style="background-color:#d4af37; border-color:#b8860b; color:#fff;">✏ ແກ້ໄຂ</a>
        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-delete" style="background-color:#a0522d; border-color:#8b4513; color:#fff;">🗑 ລົບ</a>
    </td>
</tr>
<?php $i++; endwhile; ?>
</tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// แจ้งเตือนหลังเพิ่ม/ลบ
<?php if($message): ?>
Swal.fire({
    icon: '<?= $message_type ?>',
    title: 'ສຳເລັດ!',
    text: '<?= htmlspecialchars($message, ENT_QUOTES) ?>',
    confirmButtonColor: '#b8860b',
    background: '#fff8e1',
    color: '#5c3a00'
});
<?php endif; ?>

// ยืนยันก่อนลบ
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e){
        e.preventDefault();
        let url = this.getAttribute('href');
        Swal.fire({
            title: 'ຢືນຢັນການລົບຜູ້ໃຊ້?',
            text: "ລົບແລ້ວຈະບໍ່ສາມາດກູ້ຄືນ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b8860b',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'ລົບເລີຍ',
            cancelButtonText: 'ຍົກເລີກ',
            background: '#fff8e1',
            color: '#5c3a00'
        }).then((result)=>{
            if(result.isConfirmed){
                window.location.href = url;
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
