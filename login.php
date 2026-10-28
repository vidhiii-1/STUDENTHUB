<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (!empty($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}

$error = '';
$email = '';
$info = '';

if (isset($_GET['timeout'])) {
    $info = 'Your session has expired due to 30 minutes of inactivity. Please log in again.';
}

if (!empty($_SESSION['registration_success'])) {
    $success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
} else {
    $success = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter a valid email address and password.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'SELECT id, full_name, email, password_hash, role, status
                 FROM users WHERE email = ? LIMIT 1'
            );
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (
                $user &&
                $user['status'] === 'active' &&
                password_verify($password, $user['password_hash'])
            ) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time();

                $update = $pdo->prepare(
                    'UPDATE users SET last_login = NOW() WHERE id = ?'
                );
                $update->execute([$user['id']]);

                $log = $pdo->prepare(
                    'INSERT INTO audit_logs (user_id, action, details)
                     VALUES (?, ?, ?)'
                );
                $log->execute([
                    $user['id'],
                    'USER_LOGIN',
                    'Successful login'
                ]);

                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            }

            $error = 'Invalid email or password. Please try again.';
        } catch (PDOException $e) {
            error_log('StudentHub login failed: ' . $e->getMessage());
            $error = 'Unable to sign in right now. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | StudentHub</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>

<header class="site-header">
    <a href="index.php" class="brand">
        <span class="brand-mark">S</span>
        <span>Student<span class="brand-light">Hub</span></span>
    </a>
    <nav class="main-nav">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link" href="courses.php">Courses</a>
        <a class="nav-link" href="events.php">Events</a>
    </nav>
    <div class="header-actions">
        <button class="theme-toggle" id="themeToggle" type="button"
                aria-label="Switch colour theme">◐</button>
        <a href="register.php" class="btn btn-primary btn-small">Register ↗</a>
    </div>
</header>

<main class="auth-layout">
    <section class="auth-intro">
        <span class="eyebrow">WELCOME BACK</span>
        <h1>Your campus life, <span>all in one place.</span></h1>
        <p>Sign in to access your StudentHub dashboard, courses, events and important campus updates.</p>

        <div class="auth-benefit">
            <span>✓</span>
            <div>
                <strong>Your personal dashboard</strong>
                <p>Find your student information in one place.</p>
            </div>
        </div>
        <div class="auth-benefit">
            <span>✓</span>
            <div>
                <strong>Stay connected</strong>
                <p>Keep up with events and campus announcements.</p>
            </div>
        </div>
    </section>

    <section class="auth-card">
        <h2>Welcome back</h2>
        <p class="auth-subtitle">Enter your details to sign in.</p>

        <?php if ($info): ?>
            <div class="form-alert" role="status"
                 style="color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe;margin-bottom:16px;">
                <?= htmlspecialchars($info, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="form-alert" role="status"
                 style="color:#24734f;background:#e4f7ed">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="form-alert error" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email"
                       autocomplete="username" maxlength="150"
                       value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                       required>
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password"
                       autocomplete="current-password" required>
            </div>

            <button type="submit" class="btn btn-primary auth-submit">
                Log in <span>↗</span>
            </button>
        </form>

        <p class="auth-switch">
            New to StudentHub? <a href="register.php">Create an account</a>
        </p>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
