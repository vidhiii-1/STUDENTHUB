<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$pageTitle = 'Campus Announcements & Notices | StudentHub';
$activePage = 'announcements';

if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$search = trim($_GET['search'] ?? '');
$categoryFilter = trim($_GET['category'] ?? '');

$sql = "
    SELECT a.id, a.title, a.content, a.category, a.created_at,
           u.full_name AS publisher_name
    FROM announcements a
    LEFT JOIN users u ON a.published_by = u.id
    WHERE 1 = 1
";
$params = [];

if ($search !== '') {
    $sql .= " AND (a.title LIKE ? OR a.content LIKE ?)";
    $term = '%' . $search . '%';
    $params[] = $term;
    $params[] = $term;
}

if ($categoryFilter !== '') {
    $sql .= " AND a.category = ?";
    $params[] = $categoryFilter;
}

$sql .= " ORDER BY a.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notices = $stmt->fetchAll();

// Fetch distinct categories for the filter
$categories = $pdo->query(
    "SELECT DISTINCT category FROM announcements WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC"
)->fetchAll(PDO::FETCH_COLUMN);

require_once __DIR__ . '/includes/header.php';
?>

<main class="section-wrap">
    <span class="eyebrow">CAMPUS INFORMATION &amp; UPDATES</span>
    <h1 class="page-heading">
        Official Campus <span>Notices.</span>
    </h1>
    <p class="page-intro">
        Stay updated with university circulars, academic schedules, examination alerts, and student guidelines.
    </p>

    <!-- Search & Filter Controls -->
    <section class="admin-panel" style="margin-bottom:30px;">
        <form method="GET" action="announcements.php" class="student-filters" id="announcementFilterForm">
            <div class="form-field">
                <label for="search">Search Notices</label>
                <input type="search" id="search" name="search"
                       placeholder="Search title, keywords..."
                       value="<?= h($search) ?>">
            </div>

            <div class="form-field">
                <label for="category">Category</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= h($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>>
                            <?= h($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filter Notices</button>
            <?php if ($search !== '' || $categoryFilter !== ''): ?>
                <a href="announcements.php" class="login-link">Clear filters</a>
            <?php endif; ?>
        </form>
    </section>

    <!-- Notices Grid -->
    <div class="events-grid" id="announcementsGrid">
        <?php if (empty($notices)): ?>
            <div class="empty-state" style="grid-column: 1 / -1; text-align:center; padding:50px 20px;">
                <span class="feature-icon icon-blue">📢</span>
                <h2>No announcements found</h2>
                <p>There are currently no active notices matching your search criteria.</p>
                <?php if ($search !== '' || $categoryFilter !== ''): ?>
                    <p><a href="announcements.php" class="btn btn-secondary btn-small">View all notices</a></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($notices as $notice): ?>
                <article class="event-card">
                    <div class="event-card-top">
                        <span class="event-category">
                            <?= h($notice['category']) ?>
                        </span>
                        <span style="font-size:12px; color:var(--muted); font-weight:600;">
                            <?= h(date('M d, Y', strtotime($notice['created_at']))) ?>
                        </span>
                    </div>

                    <h2 style="font-size:1.25rem; margin:12px 0 8px;">
                        <?= h($notice['title']) ?>
                    </h2>

                    <p class="event-description" style="line-height:1.65; color:var(--text); margin-bottom:16px;">
                        <?= nl2br(h($notice['content'])) ?>
                    </p>

                    <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--line); font-size:12px; color:var(--muted); display:flex; justify-content:space-between; align-items:center;">
                        <span>
                            Issued by: <strong><?= h($notice['publisher_name'] ?? 'College Administration') ?></strong>
                        </span>
                        <span style="color:#24734f; font-weight:600;">Verified &check;</span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
