<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

$errors = [];
$fullName = '';
$email = '';
$course = '';
$studyYear = '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid or expired security token. Please try again.';
    }
    $fullName = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $course = trim($_POST['course'] ?? '');
    $studyYear = $_POST['study_year'] ?? '1';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);

    if (strlen($fullName) < 2 || strlen($fullName) > 100) {
        $errors[] = 'Enter a name between 2 and 100 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if (!in_array($course, [
        'Computer Science & Engineering',
        'Information Technology',
        'Computer Engineering',
        'Electronics Engineering',
        'Mechanical Engineering'
    ], true)) {
        $errors[] = 'Please select a valid course.';
    }

    if (!in_array($studyYear, ['1', '2', '3', '4'], true)) {
        $errors[] = 'Please select a valid study year.';
    }

    if (strlen($password) < 8 || strlen($password) > 72) {
        $errors[] = 'Password must contain between 8 and 72 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$terms) {
        $errors[] = 'Please accept the terms to continue.';
    }

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            $check = $pdo->prepare(
                'SELECT id FROM users WHERE email = ?'
            );
            $check->execute([$email]);

            if ($check->fetch()) {
                $errors[] = 'An account with this email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $insert = $pdo->prepare(
                    'INSERT INTO users (full_name, email, password_hash)
                     VALUES (?, ?, ?)'
                );
                $insert->execute([$fullName, $email, $hash]);

                $userId = (int) $pdo->lastInsertId();

                $studentCode = 'SH' . str_pad(
                    (string) $userId, 6, '0', STR_PAD_LEFT
                );

                $studentInsert = $pdo->prepare(
                    'INSERT INTO students
                     (user_id, student_id, full_name, email, course, study_year)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                $studentInsert->execute([
                    $userId,
                    $studentCode,
                    $fullName,
                    $email,
                    $course,
                    (int) $studyYear
                ]);

                $log = $pdo->prepare(
                    'INSERT INTO audit_logs (user_id, action, details)
                     VALUES (?, ?, ?)'
                );
                $log->execute([
                    $userId,
                    'USER_REGISTERED',
                    'New student account created'
                ]);

                $pdo->commit();

                $_SESSION['registration_success'] =
                    'Account created successfully! You can now log in.';

                header('Location: login.php');
                exit;
            }

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log('StudentHub registration failed: ' . $e->getMessage());
            $errors[] = 'Registration could not be completed. Please try again.';
        }
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | StudentHub</title>
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
        <a href="login.php" class="login-link">Log in</a>
    </div>
</header>

<main class="auth-layout">
    <section class="auth-intro">
        <span class="eyebrow">YOUR CAMPUS, CONNECTED</span>
        <h1>Start your next chapter <span>here.</span></h1>
        <p>Create your StudentHub account to bring your courses, events and campus updates together.</p>
        <div class="auth-benefit">
            <span>✓</span>
            <div><strong>One student account</strong><p>Your personal space for campus life.</p></div>
        </div>
        <div class="auth-benefit">
            <span>✓</span>
            <div><strong>Stay in the loop</strong><p>Keep important student information close.</p></div>
        </div>
    </section>

    <section class="auth-card">
        <h2>Create your account</h2>
        <p class="auth-subtitle">Fill in your details to get started.</p>

        <?php if ($errors): ?>
            <div class="form-alert error" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <div class="form-field">
                <label for="full_name">Full name</label>
                <input id="full_name" name="full_name" type="text"
                       minlength="2" maxlength="100" autocomplete="name"
                       value="<?= e($fullName) ?>" required>
            </div>

            <div class="form-field">
                <label for="email">College email</label>
                <input id="email" name="email" type="email"
                       maxlength="150" autocomplete="email"
                       value="<?= e($email) ?>" required>
            </div>

            <div class="form-field">
                <label for="course">Course</label>
                <select id="course" name="course" required>
                    <option value="">Choose your course</option>
                    <?php
                    $courses = [
                        'Computer Science & Engineering',
                        'Information Technology',
                        'Computer Engineering',
                        'Electronics Engineering',
                        'Mechanical Engineering'
                    ];
                    foreach ($courses as $option):
                    ?>
                        <option value="<?= e($option) ?>"
                            <?= $course === $option ? 'selected' : '' ?>>
                            <?= e($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label for="study_year">Study year</label>
                <select id="study_year" name="study_year" required>
                    <option value="1" <?= $studyYear === '1' ? 'selected' : '' ?>>First year</option>
                    <option value="2" <?= $studyYear === '2' ? 'selected' : '' ?>>Second year</option>
                    <option value="3" <?= $studyYear === '3' ? 'selected' : '' ?>>Third year</option>
                    <option value="4" <?= $studyYear === '4' ? 'selected' : '' ?>>Fourth year</option>
                </select>
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password"
                       minlength="8" maxlength="72"
                       autocomplete="new-password" required>
                <small>Use at least 8 characters.</small>
            </div>

            <div class="form-field">
                <label for="confirm_password">Confirm password</label>
                <input id="confirm_password" name="confirm_password"
                       type="password" minlength="8" maxlength="72"
                       autocomplete="new-password" required>
            </div>

            <label class="terms-row">
                <input type="checkbox" name="terms" value="1" required
                    <?= isset($_POST['terms']) ? 'checked' : '' ?>>
                <span>I agree to the portal's terms of use.</span>
            </label>

            <button type="submit" class="btn btn-primary auth-submit">
                Create account <span>↗</span>
            </button>
        </form>

        <p class="auth-switch">Already registered? <a href="login.php">Log in</a></p>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
