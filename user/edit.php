<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$id = $_GET['id'] ?? null;
if (!$id) { header("Location:index.php"); exit; }

$error = '';
$success = '';

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role='user'");
$stmt->bind_param("i",$id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
if(!$user){ header("Location:index.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if(!$username || !$email){
        $error="ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ";
    } elseif($password && $password!==$password_confirm){
        $error="ລະຫັດບໍ່ກົງກັນ";
    } else {
        $stmt=$conn->prepare("SELECT id FROM users WHERE username=? AND id!=?");
        $stmt->bind_param("si",$username,$id);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $error="ຊື່ນີ້ມີຢູ່ແລ້ວ";
        } else {
            if($password){
                $hashed=password_hash($password,PASSWORD_DEFAULT);
                $stmt=$conn->prepare("UPDATE users SET username=?, email=?, password=?, updated_at=NOW() WHERE id=? AND role='user'");
                $stmt->bind_param("sssi",$username,$email,$hashed,$id);
            } else {
                $stmt=$conn->prepare("UPDATE users SET username=?, email=?, updated_at=NOW() WHERE id=? AND role='user'");
                $stmt->bind_param("ssi",$username,$email,$id);
            }
            if($stmt->execute()){
                $success="ແກ້ໄຂຜູ້ໃຊ້ສໍາເລັດ";
            } else { $error="ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ"; }
        }
    }
}

include '../includes/header.php';
?>

<style>
.center-container { display:flex; justify-content:center; align-items:center; min-height:80vh; padding:20px; }
.center-container form { width:100%; max-width:500px; background:#fff8e1; padding:25px; border-radius:10px; border:1px solid #d4af37; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
</style>

<div class="center-container">
    <form method="post">
        <h2 style="color:#b8860b; text-align:center; margin-bottom:20px;">✏ ແກ້ໄຂຜູ້ໃຊ້</h2>

        <?php if($error): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({ icon:'error', title:'<?= htmlspecialchars($error,ENT_QUOTES) ?>', background:'#fff8e1', color:'#5c3a00', confirmButtonColor:'#b8860b' });
        </script>
        <?php endif; ?>

        <?php if($success): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({ icon:'success', title:'<?= htmlspecialchars($success,ENT_QUOTES) ?>', background:'#fff8e1', color:'#5c3a00', confirmButtonColor:'#b8860b' }).then(()=>{ window.location.href='index.php'; });
        </script>
        <?php endif; ?>

        <div class="mb-3">
            <label>ຊື່ຜູ້ໃຊ້</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['username']) ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="mb-3">
            <label>ລະຫັດ (ຖ້າຕ້ອງການປ່ຽນ)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>ຢືນຢັນລະຫັດ</label>
            <input type="password" name="password_confirm" class="form-control">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn" style="background-color:#b8860b; color:#fff;">ບັນທຶກ</button>
            <a href="index.php" class="btn btn-secondary">ກັບຄືນ</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
