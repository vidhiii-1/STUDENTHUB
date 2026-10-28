
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'StudentHub') ?></title>
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
        <a class="nav-link" href="announcements.php">Announcements</a>
    </nav>

    <div class="header-actions">
        <button class="theme-toggle" id="themeToggle"
                type="button" aria-label="Switch colour theme">◐</button>
        <a href="login.php" class="btn btn-primary btn-small">Log in</a>
        <a href="register.php" class="btn btn-secondary btn-small">Register</a>
    </div>
</header>
