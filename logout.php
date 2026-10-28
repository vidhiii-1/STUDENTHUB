<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (!empty($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO audit_logs (user_id, action, details)
             VALUES (?, ?, ?)'
        );
        $stmt->execute([
            $_SESSION['user_id'],
            'USER_LOGOUT',
            'User logged out'
        ]);
    } catch (PDOException $e) {
        error_log('StudentHub logout audit failed: ' . $e->getMessage());
    }
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => 'Lax'
    ]);
}

session_destroy();

header('Location: login.php');
exit;
