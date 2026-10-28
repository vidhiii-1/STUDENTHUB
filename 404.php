<?php
http_response_code(404);
require_once __DIR__ . '/includes/auth.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found | StudentHub</title>
    <link rel="stylesheet" href="/StudentHub/assets/css/style.css">
    <script src="/StudentHub/assets/js/script.js" defer></script>
</head>
<body>

<header class="site-header">
    <a href="/StudentHub/index.php" class="brand">
        <span class="brand-mark">S</span>
        <span>Student<span class="brand-light">Hub</span></span>
    </a>
    <div class="header-actions">
        <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch colour theme">◐</button>
        <?php if ($user): ?>
            <a href="/StudentHub/logout.php" class="btn btn-primary btn-small">Log out</a>
        <?php else: ?>
            <a href="/StudentHub/login.php" class="btn btn-primary btn-small">Log in</a>
        <?php endif; ?>
    </div>
</header>

<main class="section-wrap" style="text-align:center; padding: 80px 20px;">
    <span class="eyebrow" style="color:var(--muted)">ERROR HANDLING (WDF PRACTICAL 14)</span>
    <h1 style="font-size:3rem; margin:16px 0 8px; font-family:'Manrope',sans-serif;">404 — Page Not Found</h1>
    <p style="color:var(--muted); max-width:540px; margin:0 auto 30px; font-size:1.1rem;">
        The page or academic resource you requested could not be found or may have been moved.
    </p>

    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
        <?php if (!empty($user)): ?>
            <a href="/StudentHub/dashboard.php" class="btn btn-primary">Go to Student Dashboard &rarr;</a>
        <?php else: ?>
            <a href="/StudentHub/index.php" class="btn btn-primary">Return to Homepage &rarr;</a>
        <?php endif; ?>
        <a href="/StudentHub/courses.php" class="btn btn-secondary">Explore Courses</a>
    </div>
</main>

<footer class="site-footer">
    <a href="/StudentHub/index.php" class="brand footer-brand">
        <span class="brand-mark">S</span>
        <span>Student<span class="brand-light">Hub</span></span>
    </a>
    <p>A little more organised. A lot more connected.</p>
    <span>&copy; 2026 StudentHub Portal &bull; WDF Practicals Project</span>
</footer>

</body>
</html>
