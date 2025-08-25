<?php
require_once '../config.php';
require_once '../auth.php';
checkLogin();
checkAdmin();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'admin'");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!$username || !$email) {
        $error = "ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ";
    } elseif ($password && strlen($password) < 6) {
        $error = "ລະຫັດຜ່ານຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ";
    } elseif ($password && $password !== $password_confirm) {
        $error = "ລະຫັດຜ່ານບໍ່ກົງກັນ";
    } else {
        // ກວດສອບຊື່ຜູ້ໃຊ້ຊ້ຳ
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "ຊື່ຜູ້ໃຊ້ນີ້ມີຢູ່ແລ້ວ";
        } else {
            if ($password) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, updated_at=NOW() WHERE id=? AND role='admin'");
                $stmt->bind_param("sssi", $username, $email, $hashed, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, email=?, updated_at=NOW() WHERE id=? AND role='admin'");
                $stmt->bind_param("ssi", $username, $email, $id);
            }

            if ($stmt->execute()) {
                $success = "ແກ້ໄຂຂໍ້ມູນແອັດມິນສຳເລັດ";
            } else {
                $error = "ເກີດຂໍ້ຜິດພາດໃນການບັນທຶກ";
            }
        }
    }
}

include '../includes/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
main {
    min-height: calc(100vh - 160px);
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
        <h2 style="color:#b8860b; margin-bottom:20px;">✏ ແກ້ໄຂແອັດມິນ</h2>
        <form method="post" id="editForm">
            <div class="mb-3">
                <label>ຊື່ຜູ້ໃຊ້</label>
                <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>">
            </div>
            <div class="mb-3">
                <label>ອີເມວ</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>">
            </div>
            <div class="mb-3">
                <label>ລະຫັດຜ່ານ (ຖ້າຕ້ອງການປ່ຽນ)</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="mb-3">
                <label>ຢືນຢັນລະຫັດຜ່ານ</label>
                <input type="password" name="password_confirm" id="password_confirm" class="form-control">
            </div>
            <button type="submit" class="btn" style="background-color:#b8860b; color:#fff;">ບັນທຶກ</button>
            <a href="index.php" class="btn btn-secondary" style="margin-left:10px;">ກັບຄືນ</a>
        </form>
    </div>
</main>

<script>
document.getElementById('editForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('password').value;
    const pass2 = document.getElementById('password_confirm').value;

    if (pass && pass.length < 6) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'ລະຫັດຜ່ານສັ້ນເກີນໄປ',
            text: 'ກະລຸນາໃສ່ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ',
            confirmButtonColor: '#b8860b'
        });
        return;
    }
    if (pass && pass !== pass2) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'ລະຫັດຜ່ານບໍ່ກົງກັນ',
            text: 'ກະລຸນາປ້ອນໃຫ້ກົງກັນ',
            confirmButtonColor: '#b8860b'
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
