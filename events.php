<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

$pageTitle = 'Campus Events | StudentHub';
$activePage = 'events';

$eventMessage = $_SESSION['event_message'] ?? '';
unset($_SESSION['event_message']);

$isLoggedIn = !empty($_SESSION['user_id']);
$userId = $isLoggedIn ? (int) $_SESSION['user_id'] : 0;
$userRole = $_SESSION['role'] ?? '';

// Find events the current user has registered for.
$registeredEventIds = [];

if ($isLoggedIn && $userRole === 'student') {
    $stmt = $pdo->prepare(
        'SELECT event_id FROM registrations WHERE user_id = ?'
    );
    $stmt->execute([$userId]);

    $registeredEventIds = array_map(
        'intval',
        $stmt->fetchAll(PDO::FETCH_COLUMN)
    );
}

// Fetch upcoming events that are open for registration.
$events = $pdo->query("
    SELECT id, title, description, category, event_date,
           venue, status, poster
    FROM events
    WHERE status = 'open'
      AND event_date >= NOW()
    ORDER BY event_date ASC
")->fetchAll();

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<main class="section-wrap">

    <?php if ($eventMessage !== ''): ?>
        <div class="form-alert" role="status">
            <?= h($eventMessage) ?>
        </div>
    <?php endif; ?>

    <span class="eyebrow">WHAT'S HAPPENING ON CAMPUS?</span>

    <h1 class="page-heading">
        Find your next <span>experience.</span>
    </h1>

    <p class="page-intro">
        Workshops, cultural activities and campus meetups —
        find something worth showing up for.
    </p>

    <div class="events-grid">
        <?php if (!$events): ?>
            <div class="empty-state">
                <span class="feature-icon icon-purple">✳</span>
                <h2>No upcoming events just yet</h2>
                <p>Check back soon for the latest campus activities.</p>
            </div>
        <?php else: ?>

            <?php foreach ($events as $event): ?>
                <article class="event-card">

                <?php if (!empty($event['poster'])): ?>
    <img
        src="uploads/<?= rawurlencode(basename($event['poster'])) ?>"
        alt="<?= h($event['title']) ?> poster"
        class="event-poster"
        loading="lazy"
    >
<?php endif; ?>

                    <div class="event-card-top">
                        <span class="event-category">
                            <?= h($event['category']) ?>
                        </span>
                        <span class="event-symbol" aria-hidden="true">✳</span>
                    </div>

                    <h2><?= h($event['title']) ?></h2>

                    <p class="event-description">
                        <?= nl2br(h($event['description'])) ?>
                    </p>

                    <div class="event-meta">
                        <span>
                            ◷ <?= h(date(
                                'd M Y, h:i A',
                                strtotime($event['event_date'])
                            )) ?>
                        </span>
                        <span>⌖ <?= h($event['venue']) ?></span>
                    </div>

                    <?php if (!$isLoggedIn): ?>

                        <a class="card-link" href="login.php">
                            Log in to register ↗
                        </a>

                    <?php elseif ($userRole === 'admin'): ?>

                        <p class="card-link">
                            Event managed by StudentHub Admin
                        </p>

                    <?php elseif (in_array(
                        (int) $event['id'],
                        $registeredEventIds,
                        true
                    )): ?>

                        <button type="button"
                                class="btn btn-primary"
                                disabled>
                            Already Registered ✓
                        </button>

                    <?php else: ?>

                        <form method="POST"
                              action="register-event.php"
                              class="event-register-form">

                            <input type="hidden"
                                   name="csrf_token"
                                   value="<?= h($_SESSION['csrf_token']) ?>">

                            <input type="hidden"
                                   name="event_id"
                                   value="<?= (int) $event['id'] ?>">

                            <button type="submit"
                                    class="btn btn-primary">
                                Register for event ↗
                            </button>
                        </form>

                    <?php endif; ?>

                </article>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
