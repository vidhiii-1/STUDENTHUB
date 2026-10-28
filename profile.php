<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

requireLogin();

$user = currentUser();
$pageTitle = 'My Profile | StudentHub';
$activePage = 'profile';

if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$errors = [];
$successMessage = $_SESSION['profile_success'] ?? '';
unset($_SESSION['profile_success']);

// Fetch complete profile from users and students
$stmt = $pdo->prepare("
    SELECT u.id AS user_id, u.full_name, u.email, u.role, u.status AS user_status,
           u.created_at AS account_created, u.last_login,
           s.id AS student_record_id, s.student_id, s.course, s.study_year,
           s.gender, s.mobile, s.status AS student_status
    FROM users u
    LEFT JOIN students s ON u.id = s.user_id
    WHERE u.id = ?
    LIMIT 1
");
$stmt->execute([$user['id']]);
$profile = $stmt->fetch();

if (!$profile) {
    http_response_code(404);
    exit('User profile not found.');
}

// Handle permissible contact details update
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Invalid security token. Please refresh and try again.');
    }

    $mobile = trim($_POST['mobile'] ?? '');
    $gender = trim($_POST['gender'] ?? '');

    if ($mobile !== '' && (strlen($mobile) > 20 || !preg_match('/^[0-9+\s\-()]+$/', $mobile))) {
        $errors[] = 'Enter a valid mobile contact number (digits, spaces, +, or -).';
    }

    if ($gender !== '' && !in_array($gender, ['Male', 'Female', 'Other'], true)) {
        $errors[] = 'Please select a valid gender option.';
    }

    if (empty($errors)) {
        try {
            if ($profile['student_record_id']) {
                $upd = $pdo->prepare("
                    UPDATE students
                    SET mobile = ?, gender = ?
                    WHERE user_id = ?
                ");
                $upd->execute([
                    $mobile !== '' ? $mobile : null,
                    $gender !== '' ? $gender : null,
                    $user['id']
                ]);
            }

            // Record audit log
            $log = $pdo->prepare("
                INSERT INTO audit_logs (user_id, action, details)
                VALUES (?, 'PROFILE_UPDATED', 'Updated student contact details')
            ");
            $log->execute([$user['id']]);

            $_SESSION['profile_success'] = 'Profile details updated successfully!';
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            error_log('StudentHub profile update failed: ' . $e->getMessage());
            $errors[] = 'Unable to update profile. Please try again.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="section-wrap">
    <span class="eyebrow">STUDENT ACCOUNT &amp; IDENTITY</span>
    <h1 class="page-heading">
        Student <span>Profile.</span>
    </h1>
    <p class="page-intro">
        View your official university enrolment information and update your contact preferences.
    </p>

    <?php if ($successMessage): ?>
        <div class="form-alert" role="status" style="color:#24734f; background:#e4f7ed; margin-bottom:24px;">
            <?= h($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="form-alert error" role="alert" style="margin-bottom:24px;">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= h($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:30px; margin-top:20px;">

        <!-- Left: Official Academic Identity Card -->
        <section class="admin-panel" style="margin:0;">
            <div style="display:flex; align-items:center; gap:18px; margin-bottom:24px; padding-bottom:18px; border-bottom:1px solid var(--line);">
                <div class="brand-mark" style="width:56px; height:56px; font-size:26px; font-weight:800;">
                    <?= strtoupper(substr($profile['full_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 style="margin:0; font-size:1.35rem; font-family:'Manrope',sans-serif;">
                        <?= h($profile['full_name']) ?>
                    </h2>
                    <span class="role-badge" style="margin-top:6px; display:inline-block;">
                        <?= h(ucfirst($profile['role'])) ?> &bull; <?= h(ucfirst($profile['user_status'])) ?>
                    </span>
                </div>
            </div>

            <div style="display:flex; flex-direction:column; gap:16px;">
                <div>
                    <span style="font-size:12px; color:var(--muted); display:block; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Student ID / Roll No</span>
                    <strong style="font-size:15px;"><?= h($profile['student_id'] ?? 'SH00' . str_pad($user['id'], 4, '0', STR_PAD_LEFT)) ?></strong>
                </div>

                <div>
                    <span style="font-size:12px; color:var(--muted); display:block; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Registered Email</span>
                    <span style="font-size:15px;"><?= h($profile['email']) ?></span>
                </div>

                <div>
                    <span style="font-size:12px; color:var(--muted); display:block; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Degree Program / Course</span>
                    <span style="font-size:15px; font-weight:600;"><?= h($profile['course'] ?? 'Engineering & Technology') ?></span>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div>
                        <span style="font-size:12px; color:var(--muted); display:block; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Study Year</span>
                        <span style="font-size:15px;">Year <?= (int) ($profile['study_year'] ?? 1) ?></span>
                    </div>

                    <div>
                        <span style="font-size:12px; color:var(--muted); display:block; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Portal Joined</span>
                        <span style="font-size:14px; color:var(--muted);"><?= h(date('M d, Y', strtotime($profile['account_created']))) ?></span>
                    </div>
                </div>

                <?php if (!empty($profile['last_login'])): ?>
                    <div style="font-size:12px; color:var(--muted); padding-top:12px; border-top:1px dashed var(--line);">
                        Last signed in: <?= h(date('M d, Y &bull; h:i A', strtotime($profile['last_login']))) ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Right: Contact Details Editable Form -->
        <section class="admin-panel" style="margin:0;">
            <div class="admin-panel-heading">
                <div>
                    <span class="eyebrow">UPDATE PREFERENCES</span>
                    <h2>Contact Information</h2>
                </div>
            </div>

            <p style="font-size:13px; color:var(--muted); margin-bottom:20px;">
                You may update your mobile contact number and personal preferences. Academic fields like degree program and Student ID must be updated through the administration office.
            </p>

            <form method="POST" action="profile.php">
                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                <div class="form-field">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile"
                           placeholder="+91 98765 43210"
                           maxlength="20"
                           value="<?= h($profile['mobile'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select gender</option>
                        <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= ($profile['gender'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top:10px;">
                    Save Profile Changes &rarr;
                </button>
            </form>
        </section>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
