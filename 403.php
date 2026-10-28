<?php
http_response_code(403);
require_once __DIR__ . '/includes/auth.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Access Denied | StudentHub</title>
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
    <span class="eyebrow" style="color:#d9534f">SECURITY &amp; AUTHORIZATION (WDF PRACTICAL 14)</span>
    <h1 style="font-size:3rem; margin:16px 0 8px; font-family:'Manrope',sans-serif;">403 — Access Denied</h1>
    <p style="color:var(--muted); max-width:540px; margin:0 auto 30px; font-size:1.1rem;">
        You do not have permission to access this resource. Administrator privileges are required.
    </p>

    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
        <?php if (!empty($user)): ?>
            <a href="/StudentHub/dashboard.php" class="btn btn-primary">Go to Student Dashboard &rarr;</a>
        <?php else: ?>
            <a href="/StudentHub/login.php" class="btn btn-primary">Log in as Administrator &rarr;</a>
        <?php endif; ?>
        <a href="/StudentHub/index.php" class="btn btn-secondary">StudentHub Home</a>
    </div>
</main>

<footer class="site-footer">
    <a href="/StudentHub/index.php" class="brand footer-brand">
        <span class="brand-mark">S</span>
        <span>Student<span class="brand-light">Hub</span></span>
    </a>
    <p>A little more organised. A lot more connected.</p>
    <span>&copy; 2026 StudentHub Portal</span>
</footer>

</body>
</html>
