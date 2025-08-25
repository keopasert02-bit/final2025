<?php
session_start();
require_once 'config.php';
session_regenerate_id(true);

if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'pages/index.php';
    header("Location: $redirect");
    exit;
}

$login_error = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $login_error = true;
        $error_message = 'ຂໍ້ຜິດພາດດ້ານຄວາມປອດໄພ';
    } else {
        $usernameOrEmail = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($usernameOrEmail) || empty($password)) {
            $login_error = true;
            $error_message = 'ກະລຸນາໃສ່ຊື່ຜູ້ໃຊ້ແລະລະຫັດຜ່ານ';
        } elseif (strlen($password) < 6) {
            $login_error = true;
            $error_message = 'ລະຫັດຜ່ານຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ';
        } else {
            try {
                $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE (username = ? OR email = ?)");
                if (!$stmt) throw new Exception('Database prepare error: ' . $conn->error);

                $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['login_time'] = time();

                    $redirect = ($user['role'] === 'admin') ? 'admin_dashboard.php' : 'pages/index.php';
                    header("Location: $redirect");
                    exit;
                } else {
                    $login_error = true;
                    $error_message = 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ';
                    error_log("Failed login attempt for: " . $usernameOrEmail . " from IP: " . $_SERVER['REMOTE_ADDR']);
                }

                $stmt->close();
            } catch (Exception $e) {
                $login_error = true;
                $error_message = 'ເກີດຂໍ້ຜິດພາດລະບົບ ກະລຸນາລອງໃໝ່';
                error_log("Login error: " . $e->getMessage());
            }
        }
    }
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ເຂົ້າລະບົບ - ລະບົບວັດ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #8B4513;
            --primary-light: #d2a679;
            --primary-dark: #654321;
            --background-gradient: linear-gradient(135deg, #f8f3ed 0%, #f5ebe0 100%);
            --transition: all 0.3s ease;
        }
        body {
            font-family: 'Noto Sans Lao', sans-serif;
            background: var(--background-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .login-wrapper {
            background: #fff;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0;
            text-align: center;
        }
        .login-header h4 {
            margin: 0;
            font-weight: bold;
        }
        .subtitle {
            font-size: 0.9rem;
            margin-top: 0.5rem;
            opacity: 0.9;
        }
        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            font-weight: bold;
            border-radius: 12px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
        }
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            margin-right: 10px;
            display: none;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .register-link {
            font-size: 0.9rem;
            color: var(--primary-color);
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-header">
        <h4><i class="bi bi-shield-lock me-1"></i>ເຂົ້າສູ່ລະບົບ</h4>
        <div class="subtitle">ຍິນດີຕ້ອນຮັບ ສູ່ລະບົບຈັດການຂອງວັດ</div>
    </div>
    <div class="p-4">
        <?php if ($login_error): ?>
        <div class="alert alert-danger rounded-3">
            <i class="bi bi-exclamation-circle me-1"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <form method="POST" id="loginForm" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="form-floating mb-3">
                <input type="text" name="username" id="username" class="form-control"
                       placeholder="ຊື່ຜູ້ໃຊ້ ຫຼື ອີເມວ"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                       required autofocus>
                <label for="username"><i class="bi bi-person me-1"></i> ຊື່ຜູ້ໃຊ້ ຫຼື ອີເມວ</label>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" name="password" id="password" class="form-control" placeholder="ລະຫັດຜ່ານ" required>
                <label for="password"><i class="bi bi-lock me-1"></i> ລະຫັດຜ່ານ</label>
                <button type="button" class="password-toggle" id="togglePassword"><i class="bi bi-eye" id="toggleIcon"></i></button>
            </div>

            <div class="text-end mb-3">
                <a href="forgot_admin.php" class="register-link">ລືມລະຫັດຜ່ານ?</a>
            </div>

            <button type="submit" class="btn btn-login w-100" id="loginBtn">
                <div class="loading-spinner" id="loadingSpinner"></div>
                <span id="loginText"><i class="bi bi-box-arrow-in-right me-1"></i>ເຂົ້າລະບົບ</span>
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        const isVisible = passwordField.type === 'text';
        passwordField.type = isVisible ? 'password' : 'text';
        toggleIcon.className = isVisible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    document.getElementById('loginForm').addEventListener('submit', function () {
        const loginBtn = document.getElementById('loginBtn');
        loginBtn.disabled = true;
        document.getElementById('loadingSpinner').style.display = 'inline-block';
        document.getElementById('loginText').style.opacity = '0.7';
    });

    document.getElementById('password').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            document.getElementById('loginBtn').click();
        }
    });

    <?php if ($login_error): ?>
    Swal.fire({
        icon: 'error',
        title: 'ການເຂົ້າລະບົບລົ້ມເຫຼວ!',
        text: '<?php echo htmlspecialchars($error_message); ?>',
        confirmButtonText: 'ຕົກລົງ',
        confirmButtonColor: '#8B4513',
        backdrop: 'rgba(0,0,0,0.4)',
        allowOutsideClick: false,
        customClass: {
            popup: 'swal2-border-radius',
            title: 'fs-5',
            content: 'fs-6'
        }
    });
    <?php endif; ?>
</script>
</body>
</html>
