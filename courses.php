<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

$pageTitle = 'Explore Courses | StudentHub';
$activePage = 'courses';

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$search = trim($_GET['search'] ?? '');
$semester = trim($_GET['semester'] ?? '');

$sql = "SELECT * FROM courses WHERE 1 = 1";
$params = [];

if ($search !== '') {
    $sql .= " AND (
        course_code LIKE ?
        OR course_name LIKE ?
        OR description LIKE ?
        OR instructor LIKE ?
    )";

    $term = '%' . $search . '%';
    $params = [$term, $term, $term, $term];
}

if ($semester !== '' && ctype_digit($semester)) {
    $sql .= " AND semester = ?";
    $params[] = (int) $semester;
}

$sql .= " ORDER BY course_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

$semesters = $pdo->query(
    "SELECT DISTINCT semester
     FROM courses
     WHERE semester IS NOT NULL
     ORDER BY semester ASC"
)->fetchAll(PDO::FETCH_COLUMN);

require_once __DIR__ . '/includes/header.php';
?>

<main class="admin-page">
    <span class="eyebrow">STUDENTHUB / LEARNING</span>

    <h1 class="admin-heading">Explore Courses</h1>
    <p style="color: var(--muted);">
        Find courses by name, code, instructor, or semester.
    </p>

    <section class="admin-panel">
        <form method="GET" action="courses.php" class="student-filters" id="courseFilterForm">

            <div class="form-field">
                <label for="search">Search Courses</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    placeholder="Course name, code, instructor..."
                    value="<?= h($search) ?>"
                >
            </div>

            <div class="form-field">
                <label for="semester">Filter by Semester</label>
                <select id="semester" name="semester">
                    <option value="">All Semesters</option>

                    <?php foreach ($semesters as $item): ?>
                        <option
                            value="<?= h($item) ?>"
                            <?= (string) $semester === (string) $item ? 'selected' : '' ?>
                        >
                            Semester <?= h($item) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Apply Filters
            </button>

            <a href="courses.php" class="login-link">Clear Filters</a>
        </form>
    </section>

    <section class="admin-panel">
        <h2 id="coursesHeading">Available Courses (<?= count($courses) ?>)</h2>

        <div id="coursesContainer">
            <?php if (empty($courses)): ?>
                <p>No courses found. Try changing your search or filters.</p>
            <?php else: ?>
                <div class="event-grid">

                <?php foreach ($courses as $courseItem): ?>
                    <article class="event-card">

                        <div class="event-card-top">
                            <span class="event-category">
                                <?= h($courseItem['course_code']) ?>
                            </span>

                            <?php if ($courseItem['semester'] !== null): ?>
                                <span>
                                    Semester <?= h($courseItem['semester']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2><?= h($courseItem['course_name']) ?></h2>

                        <p>
                            <?= nl2br(h(
                                $courseItem['description'] ?? 'No description available.'
                            )) ?>
                        </p>

                        <p>
                            <strong>Instructor:</strong>
                            <?= h($courseItem['instructor'] ?? 'Not assigned') ?>
                        </p>

                    </article>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
