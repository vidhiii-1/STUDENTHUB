
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    $lastActivity = $_SESSION['last_activity'] ?? time();

    if (time() - $lastActivity > 1800) {
        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: /StudentHub/login.php?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}
