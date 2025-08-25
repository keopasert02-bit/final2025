<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$error = '';
$username = '';
$email = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $role = 'user';

    if(!$username || !$email || !$password){
        $error = "ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບ";
    } elseif($password !== $password_confirm){
        $error = "ລະຫັດຜ່ານບໍ່ກົງກັນ";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss",$username,$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $error = "ຊື່ຜູ້ໃຊ້ ຫຼື email ມີແລ້ວ";
        } else {
            $hashed = password_hash($password,PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users(username,email,password,role,created_at,updated_at) VALUES(?,?,?,?,NOW(),NOW())");
            $stmt->bind_param("ssss",$username,$email,$hashed,$role);
            if($stmt->execute()){
                header("Location: index.php?message=ເພີ່ມຜູ້ໃຊ້ສໍາເລັດ&type=success");
                exit;
            } else {
                $error = "ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ";
            }
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
        <h2 style="color:#b8860b; text-align:center; margin-bottom:20px;">➕ ເພີ່ມຜູ້ໃຊ້</h2>

        <?php if($error): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({ icon:'error', title:'<?= htmlspecialchars($error,ENT_QUOTES) ?>', background:'#fff8e1', color:'#5c3a00', confirmButtonColor:'#b8860b' });
        </script>
        <?php endif; ?>

        <div class="mb-3">
            <label>ຊື່ຜູ້ໃຊ້</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($username) ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($email) ?>">
        </div>
        <div class="mb-3">
            <label>ລະຫັດ</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>ຢືນຢັນລະຫັດ</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn" style="background-color:#b8860b; color:#fff;">ບັນທຶກ</button>
            <a href="index.php" class="btn btn-secondary">ກັບຄືນ</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
