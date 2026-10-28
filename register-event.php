<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

requireLogin();

// Only accept form submissions.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: events.php');
    exit;
}

// Verify the security token.
if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    http_response_code(403);
    exit('Invalid form token. Return to Events and try again.');
}

// Validate the event ID.
$eventId = filter_input(
    INPUT_POST,
    'event_id',
    FILTER_VALIDATE_INT
);

if (!$eventId || $eventId < 1) {
    header('Location: events.php');
    exit;
}

try {
    // Confirm the event is open and has not passed.
    $stmt = $pdo->prepare(
        "SELECT id FROM events
         WHERE id = ?
         AND status = 'open'
         AND event_date >= NOW()"
    );

    $stmt->execute([$eventId]);

    if (!$stmt->fetch()) {
        $_SESSION['event_message'] =
            'This event is no longer available for registration.';

        header('Location: events.php');
        exit;
    }

    // Save the student's registration.
    $stmt = $pdo->prepare(
        'INSERT INTO registrations (user_id, event_id)
         VALUES (?, ?)'
    );

    $stmt->execute([
        $_SESSION['user_id'],
        $eventId
    ]);

    // Record the action in the audit log.
    $stmt = $pdo->prepare(
        'INSERT INTO audit_logs (user_id, action, details)
         VALUES (?, ?, ?)'
    );

    $stmt->execute([
        $_SESSION['user_id'],
        'EVENT_REGISTERED',
        'Registered for event ID ' . $eventId
    ]);

    $_SESSION['event_message'] =
        'You are registered for the event!';

} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        $_SESSION['event_message'] =
            'You have already registered for this event.';
    } else {
        error_log(
            'StudentHub event registration failed: ' .
            $e->getMessage()
        );

        $_SESSION['event_message'] =
            'Registration failed. Please try again.';
    }
}

// Return to the events page.
header('Location: events.php');
exit;
