<?php 
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $role = 'admin';

    if (!$username || !$email || !$password) {
        $error = "ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ";
    } elseif ($password !== $password_confirm) {
        $error = "ລະຫັດບໍ່ກົງກັນ";
    } elseif (strlen($password) < 6) {
        $error = "ລະຫັດຕ້ອງມີ 6 ຕົວຂຶ້ນໄປ";
    } else {
        // เช็ค username ซ้ำ
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "ຊື່ຜູ້ໃຊ້ນີ້ມີຢູ່ແລ້ວ";
        } else {
            // เช็ค email ซ้ำ
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "ອີເມວນີ້ມີຢູ່ແລ້ວ";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username,email,password,role,created_at,updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                $stmt->bind_param("ssss", $username, $email, $hashed, $role);
                if ($stmt->execute()) {
                    $success = "ເພີ່ມແອັດມິນສຳເລັດ";
                } else {
                    $error = "ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ";
                }
            }
        }
    }
}

include '../includes/header.php';
?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
main {
    min-height: calc(100vh - 160px); /* ลบความสูง header+footer */
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff8e1;
    padding: 20px;
}
.form-box {
    max-width: 500px;
    width: 100%;
    background: #fff8e1;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #d4af37;
    color: #5c3a00;
}
</style>

<?php if ($error): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'ເກີດຂໍ້ຜິດພາດ',
    text: '<?= htmlspecialchars($error, ENT_QUOTES) ?>',
    confirmButtonColor: '#b8860b',
    background: '#fff8e1',
    color: '#5c3a00'
});
</script>
<?php elseif ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'ສຳເລັດ',
    text: '<?= htmlspecialchars($success, ENT_QUOTES) ?>',
    confirmButtonColor: '#b8860b',
    background: '#fff8e1',
    color: '#5c3a00'
}).then(() => {
    window.location.href = 'index.php';
});
</script>
<?php endif; ?>

<main>
    <div class="form-box">
        <h2 style="color:#b8860b; margin-bottom:20px;">➕ ເພີ່ມແອັດມິນ</h2>
        <form method="post" id="adminForm">
            <div class="mb-3">
                <label>ຊື່ຜູ້ໃຊ້</label>
                <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>ອີເມວ</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label>ລະຫັດ</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>ຢືນຢັນລະຫັດ</label>
                <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn" style="background-color:#b8860b; color:#fff;">ບັນທຶກ</button>
            <a href="index.php" class="btn btn-secondary" style="margin-left:10px;">ກັບ</a>
        </form>
    </div>
</main>

<script>
// ตรวจสอบความยาวรหัสผ่านแบบ Real-time
document.getElementById('adminForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const pass2 = document.getElementById('password_confirm').value;

    if (pass.length < 6) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'ລະຫັດສັ້ນເກີນໄປ',
            text: 'ກະລຸນາໃສ່ລະຫັດຢ່າງນ້ອຍ 6 ຕົວ',
            confirmButtonColor: '#b8860b'
        });
        return;
    }
    if (pass !== pass2) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'ລະຫັດບໍ່ກົງກັນ',
            text: 'ກະລຸນາກວດຄືນລະຫັດ',
            confirmButtonColor: '#b8860b'
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
