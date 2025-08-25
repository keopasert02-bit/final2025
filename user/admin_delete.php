<?php
session_start();
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id<=0){ header("Location:index.php?message=ID ບໍ່ຖືກ&type=error"); exit; }

$stmt=$conn->prepare("SELECT username FROM users WHERE id=? AND role='user'");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();
$user=$result->fetch_assoc();
if(!$user){ header("Location:index.php?message=ผู้ใช้ไม่พบ&type=error"); exit; }

if(isset($_GET['confirm']) && $_GET['confirm']==1){
    $stmt=$conn->prepare("DELETE FROM users WHERE id=? AND role='user'");
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location:index.php?message=ลบผู้ใช้ '".urlencode($user['username'])."' สำเร็จ&type=success");
        exit;
    } else {
        header("Location:index.php?message=เกิดข้อผิดพลาดในการลบผู้ใช้&type=error");
        exit;
    }
}

include '../includes/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    title:'ຢືນຢັນການລົບຜູ້ໃຊ້ <?= htmlspecialchars($user['username'],ENT_QUOTES) ?>?',
    text:'ລົບແລ້ວບໍ່ສາມາດກູ້ຄືນໄດ້',
    icon:'warning',
    showCancelButton:true,
    confirmButtonColor:'#b8860b',
    cancelButtonColor:'#aaa',
    confirmButtonText:'ລົບເລີຍ',
    cancelButtonText:'ຍົກເລີກ',
    background:'#fff8e1',
    color:'#5c3a00'
}).then((result)=>{
    if(result.isConfirmed){
        window.location.href='delete.php?id=<?= $id ?>&confirm=1';
    } else {
        window.location.href='index.php';
    }
});
</script>
<?php include '../includes/footer.php'; ?>
