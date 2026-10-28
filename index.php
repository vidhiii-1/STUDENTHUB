<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'StudentHub | Your Campus, Connected';
$activePage = 'home';

require_once __DIR__ . '/includes/header.php';
?>

<main id="main-content">
    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow">
                <span class="status-dot"></span>
                YOUR CAMPUS, CONNECTED
            </div>

            <h1>Make your student life <span>count.</span></h1>

            <p class="hero-description">
                One place for your courses, campus events, important
                updates and everything that keeps your college life moving.
            </p>

            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary">
                    Join StudentHub <span>↗</span>
                </a>
                <a href="courses.php" class="btn btn-secondary">
                    Explore courses
                </a>
            </div>

            <div class="hero-note">
                <span class="avatar-stack" aria-hidden="true">
                    <span>K</span><span>M</span><span>O</span>
                </span>
                <span>Built for students. Made for campus life.</span>
            </div>
        </div>

        <div class="hero-visual" aria-label="StudentHub dashboard preview">
            <div class="visual-orbit orbit-one"></div>
            <div class="visual-orbit orbit-two"></div>

            <div class="preview-card">
                <div class="preview-top">
                    <div>
                        <span class="preview-label">STUDENT OVERVIEW</span>
                        <h2>Your day, at a glance.</h2>
                    </div>
                    <span class="preview-icon">✳</span>
                </div>

                <div class="preview-date">
                    <span class="calendar-icon">▦</span>
                    <div>
                        <strong>Stay on track</strong>
                        <span>Courses, events and updates</span>
                    </div>
                    <span class="preview-arrow">↗</span>
                </div>

                <div class="preview-stats">
                    <div class="mini-stat">
                        <span class="mini-icon lavender">▤</span>
                        <strong>Courses</strong>
                        <span>Learning hub</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-icon peach">◷</span>
                        <strong>Timetable</strong>
                        <span>Plan your day</span>
                    </div>
                </div>

                <div class="preview-bottom">
                    <span class="status-dot"></span>
                    Everything in one place
                    <span class="preview-arrow">→</span>
                </div>
            </div>

            <div class="floating-note">
                <span class="floating-note-icon">✦</span>
                <div><strong>Campus life</strong><span>Starts here.</span></div>
            </div>
        </div>
    </section>

    <section class="features section-wrap">
        <div class="section-heading">
            <div>
                <span class="eyebrow">THE STUDENT TOOLKIT</span>
                <h2>Less searching. <span>More doing.</span></h2>
            </div>
            <p>All the essentials, right where you need them.</p>
        </div>

        <div class="feature-grid">
            <a class="feature-card" href="courses.php">
                <span class="feature-icon icon-blue">▤</span>
                <span class="feature-number">01 / LEARN</span>
                <h3>Your courses</h3>
                <p>Keep your learning resources organised and easy to find.</p>
                <span class="card-link">Explore courses ↗</span>
            </a>

            <a class="feature-card" href="timetable.php">
                <span class="feature-icon icon-purple">◷</span>
                <span class="feature-number">02 / PLAN</span>
                <h3>Your timetable</h3>
                <p>Know what's coming up and make the most of your day.</p>
                <span class="card-link">View timetable ↗</span>
            </a>

            <a class="feature-card" href="events.php">
                <span class="feature-icon icon-orange">✳</span>
                <span class="feature-number">03 / CONNECT</span>
                <h3>Campus events</h3>
                <p>Discover activities, meet people and get involved.</p>
                <span class="card-link">Find events ↗</span>
            </a>

            <a class="feature-card" href="announcements.php">
                <span class="feature-icon icon-green">↗</span>
                <span class="feature-number">04 / STAY UPDATED</span>
                <h3>Campus notices</h3>
                <p>Find important announcements and student updates.</p>
                <span class="card-link">Read notices ↗</span>
            </a>
        </div>
    </section>

    <section class="join-section">
        <div>
            <span class="eyebrow">YOUR NEXT CHAPTER</span>
            <h2>College life has a lot going on.<br>Let's keep it <span>together.</span></h2>
        </div>
        <a href="register.php" class="btn btn-light">Create your account ↗</a>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
