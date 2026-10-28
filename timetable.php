<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

requireLogin();

if (!function_exists('h')) {
    function h($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

// Determine semester from the logged-in student's study year.
$userId = $_SESSION['user_id'] ?? null;
$semester = 3; // Default demo semester

if ($userId) {
    $stmt = $pdo->prepare(
        "SELECT study_year FROM students WHERE user_id = ? LIMIT 1"
    );
    $stmt->execute([$userId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && (int)$student['study_year'] >= 1 &&
        (int)$student['study_year'] <= 4) {
        $semester = ((int)$student['study_year'] * 2) - 1;
    }
}

// Read the selected semester's actual timetable.
$stmt = $pdo->prepare(
    "SELECT * FROM timetable
     WHERE semester = ?
     ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday',
     'Thursday', 'Friday'), start_time"
);
$stmt->execute([$semester]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sample timetable shown when no database records exist.
$sample = [
    'Monday' => [
        ['09:00 AM - 10:00 AM', 'Data Structures', 'Room 201'],
        ['10:00 AM - 11:00 AM', 'Computer Networks', 'Lab 1'],
        ['11:15 AM - 12:15 PM', 'Mathematics', 'Room 201'],
        ['01:00 PM - 02:00 PM', 'Web Development', 'Lab 2'],
    ],
    'Tuesday' => [
        ['09:00 AM - 10:00 AM', 'Java Programming', 'Lab 1'],
        ['10:00 AM - 11:00 AM', 'Data Structures', 'Room 201'],
        ['11:15 AM - 12:15 PM', 'Computer Networks', 'Room 202'],
        ['01:00 PM - 02:00 PM', 'Web Development', 'Lab 2'],
    ],
    'Wednesday' => [
        ['09:00 AM - 10:00 AM', 'Mathematics', 'Room 201'],
        ['10:00 AM - 11:00 AM', 'Java Programming', 'Lab 1'],
        ['11:15 AM - 12:15 PM', 'Web Development', 'Lab 2'],
        ['01:00 PM - 02:00 PM', 'Data Structures', 'Room 201'],
    ],
    'Thursday' => [
        ['09:00 AM - 10:00 AM', 'Computer Networks', 'Room 202'],
        ['10:00 AM - 11:00 AM', 'Mathematics', 'Room 201'],
        ['11:15 AM - 12:15 PM', 'Java Programming', 'Lab 1'],
        ['01:00 PM - 02:00 PM', 'Data Structures', 'Room 201'],
    ],
    'Friday' => [
        ['09:00 AM - 10:00 AM', 'Web Development', 'Lab 2'],
        ['10:00 AM - 11:00 AM', 'Computer Networks', 'Room 202'],
        ['11:15 AM - 12:15 PM', 'Mathematics', 'Room 201'],
        ['01:00 PM - 02:00 PM', 'Java Programming', 'Lab 1'],
    ],
];

$week = array_fill_keys(
    ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
    []
);

foreach ($records as $record) {
    $day = $record['day_of_week'];

    if (!isset($week[$day])) {
        continue;
    }

    $week[$day][] = [
        date('h:i A', strtotime($record['start_time'])) . ' - ' .
        date('h:i A', strtotime($record['end_time'])),
        $record['course_name'],
        $record['classroom'] ?: 'Room not assigned'
    ];
}

$hasRecords = count($records) > 0;

if (!$hasRecords) {
    $week = $sample;
}

$pageTitle = 'Class Timetable | StudentHub';
$activePage = 'timetable';

require_once __DIR__ . '/includes/header.php';
?>
<style>
    .timetable-wrap {
        overflow-x: auto;
        margin-top: 24px;
    }
    .weekly-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
        background: var(--surface);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--line);
    }
    .weekly-table th,
    .weekly-table td {
        padding: 16px;
        text-align: left;
        border: 1px solid var(--line);
        vertical-align: top;
    }
    .weekly-table th {
        background: var(--soft-blue);
        color: var(--primary-dark);
        font-weight: 700;
    }
    .weekly-table td:first-child {
        font-weight: 700;
        background: var(--bg);
        white-space: nowrap;
    }
    .class-item {
        display: block;
        margin-bottom: 8px;
        padding: 10px 12px;
        background: var(--soft-blue);
        border-radius: 8px;
        color: var(--text);
        font-size: 14px;
        border-left: 3px solid var(--primary);
    }
    .class-item:last-child { margin-bottom: 0; }
    .class-item strong { display: block; margin-bottom: 4px; color: var(--primary); }
    .sample-note {
        margin-top: 16px;
        color: var(--muted);
        font-size: 14px;
    }
</style>

<main class="admin-page">
    <span class="eyebrow">STUDENTHUB / WEEKLY SCHEDULE</span>
    <h1 class="admin-heading">Timetable</h1>
    <p style="color: var(--muted);">
        Your weekly class schedule from Monday to Friday.
    </p>

    <section class="admin-panel timetable-wrap">
        <table class="weekly-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Class Schedule</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($week as $day => $classes): ?>
                    <tr>
                        <td><?= h($day) ?></td>
                        <td>
                            <?php if (empty($classes)): ?>
                                No classes scheduled
                            <?php else: ?>
                                <?php foreach ($classes as $class): ?>
                                    <div class="class-item">
                                        <strong><?= h($class[1]) ?></strong>
                                        <?= h($class[0]) ?>
                                        <br>
                                        <span><?= h($class[2]) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <?php if (!$hasRecords): ?>
        <p class="sample-note">
            This is a sample timetable for demonstration. Actual class times
            will appear here when the admin adds timetable records.
        </p>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
