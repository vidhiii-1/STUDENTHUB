# StudentHub — Academic Portal & Student Management System

StudentHub is a comprehensive college web portal designed and developed as a capstone project synthesizing modern web development concepts from **WDF Practical 1 through WDF Practical 14**. It provides unified academic services for students and administrators, including course exploration, weekly timetables, official campus notices, coursework assignment submissions, campus event registrations, profile management, and administrative auditing.

---

## 1. Technology Stack & Prerequisites

- **Web Server:** Apache 2.4+ (XAMPP for Windows)
- **Runtime:** PHP 8.1+ / 8.4+
- **Database:** MySQL 8.0+ / MariaDB 10.4+
- **Frontend:** HTML5, Modern CSS3 (CSS Variables, Flexbox, CSS Grid), Vanilla ES6+ JavaScript
- **Security:** PDO Prepared Statements, `password_hash()` (BCRYPT), Anti-CSRF Tokens, Inactivity Timeout Guards, Strict MIME File Validation

---

## 2. Local Installation & Setup (XAMPP)

1. **Place Project in XAMPP Document Root:**
   ```text
   D:\xampp\htdocs\StudentHub
   ```
2. **Start Services in XAMPP Control Panel:**
   - Start **Apache** web server.
   - Start **MySQL** database server.
3. **Database Setup:**
   - Open phpMyAdmin at `http://localhost/phpmyadmin/` or open terminal.
   - Create database named `studenthub`:
     ```sql
     CREATE DATABASE IF NOT EXISTS studenthub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Import the baseline schema and seed data:
     ```powershell
     Get-Content database/studenthub_dump_baseline.sql | & d:\xampp\mysql\bin\mysql.exe -u root studenthub
     Get-Content database/seed_academic_data.sql | & d:\xampp\mysql\bin\mysql.exe -u root studenthub
     ```
4. **Database Configuration:**
   Verify credentials in `includes/db.php`:
   - Host: `localhost`
   - Database: `studenthub`
   - User: `root`
   - Password: `""` (empty default for XAMPP)
5. **Access the Application:**
   Open your browser and navigate to:
   ```text
   http://localhost/StudentHub/
   ```

---

## 3. Pre-Configured User Accounts

| Role | Name | Email Address | Description |
| :--- | :--- | :--- | :--- |
| **Student** | Kush Patel | `25cs072@charusat.edu.in` | Enrolled in Computer Science & Engineering (Year 2) |
| **Admin** | Kush Patel | `kush@gmail.com` | Campus administrator with full portal privileges |
| **Student** | Namra Patel | `namra@gmail.com` | Enrolled in BCA (Year 3) |

> **Self-Registration:** New students can register anytime via `register.php`. The system automatically generates a unique Student ID (e.g. `SH000004`), securely hashes passwords, initializes profile records, and logs the registration in the audit trail.

---

## 4. Comprehensive WDF Practical Mapping (Practicals 1–14)

| Practical | Web Development Topic | StudentHub Implementation & Source Files |
| :---: | :--- | :--- |
| **WDF 01** | **HTML5 Document Structure & Semantic Elements** | Clean, semantic document outlines using `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, and `<footer>` across [index.php](file:///d:/xampp/htdocs/StudentHub/index.php), [includes/header.php](file:///d:/xampp/htdocs/StudentHub/includes/header.php), and [includes/footer.php](file:///d:/xampp/htdocs/StudentHub/includes/footer.php). |
| **WDF 02** | **HTML Lists, Tables & Structured Layouts** | Responsive, accessible tabular layouts with structured `<thead>`, `<tbody>`, and `<tr>` elements in [timetable.php](file:///d:/xampp/htdocs/StudentHub/timetable.php), [admin/students.php](file:///d:/xampp/htdocs/StudentHub/admin/students.php), [admin/audit-logs.php](file:///d:/xampp/htdocs/StudentHub/admin/audit-logs.php), and [admin/announcements.php](file:///d:/xampp/htdocs/StudentHub/admin/announcements.php). |
| **WDF 03** | **HTML Forms & Validation Attributes** | Client- and server-side validated forms featuring input types, required attributes, min/max constraints, and dropdowns in [login.php](file:///d:/xampp/htdocs/StudentHub/login.php), [register.php](file:///d:/xampp/htdocs/StudentHub/register.php), [profile.php](file:///d:/xampp/htdocs/StudentHub/profile.php), and [assignments.php](file:///d:/xampp/htdocs/StudentHub/assignments.php). |
| **WDF 04** | **CSS3 Styling, Typography & Design System Tokens** | Curated CSS Custom Properties for theme tokens (`--bg`, `--surface`, `--text`, `--primary`, `--line`, `--muted`), font stacks (DM Sans, Plus Jakarta Sans), and box models in [assets/css/style.css](file:///d:/xampp/htdocs/StudentHub/assets/css/style.css). |
| **WDF 05** | **Modern CSS Layouts (Flexbox & CSS Grid)** | Two-dimensional responsive CSS Grids (`.events-grid`, `.stats-grid`, `.form-grid`) and Flexbox alignment bars across all viewports with `@media (max-width: 900px)` media queries. |
| **WDF 06** | **UI Components, Cards, Badges & Theme Switcher** | Reusable card surfaces (`.event-card`, `.admin-panel`), role status badges (`.role-badge`), smooth transition micro-interactions, and a global dark/light theme switcher persisted via `localStorage`. |
| **WDF 07** | **PHP Server-Side Templating & Dynamic Rendering** | Modular PHP architecture with reusable components in `includes/`, dynamic view generation, loop iterations, conditional state rendering, and sanitized output via `h()`. |
| **WDF 08** | **MySQL Database Integration & PDO Prepared Statements** | Robust database layer in [includes/db.php](file:///d:/xampp/htdocs/StudentHub/includes/db.php) utilizing PHP Data Objects (PDO) with prepared statements and parameter binding across all queries to prevent SQL injection. |
| **WDF 09** | **User Authentication & Role-Based Access Control** | Secure authentication workflow with BCRYPT password hashing (`password_hash`), session regeneration upon login, and route guards (`requireLogin()`, `requireAdmin()`) in [includes/auth.php](file:///d:/xampp/htdocs/StudentHub/includes/auth.php). |
| **WDF 10** | **State Management, Sessions, Timeouts & CSRF Protection** | Robust session lifecycle with a **30-minute inactivity timeout**, cryptographically secure Anti-CSRF token generation and validation in [includes/csrf.php](file:///d:/xampp/htdocs/StudentHub/includes/csrf.php). |
| **WDF 11** | **Server-Side File Generation & CSV Data Export** | On-the-fly CSV generation with appropriate `Content-Type: text/csv` and `Content-Disposition: attachment` HTTP streaming headers in [admin/students.php?export=csv](file:///d:/xampp/htdocs/StudentHub/admin/students.php). |
| **WDF 12** | **Client-Side DOM Manipulation & Event Handling** | Vanilla JavaScript in [assets/js/script.js](file:///d:/xampp/htdocs/StudentHub/assets/js/script.js) handling dynamic mobile navigation drawer toggling, theme switching, debounced search events, and localStorage persistence. |
| **WDF 13** | **AJAX & Fetch API Asynchronous Data Exchange** | Asynchronous REST JSON endpoint in [api/index.php](file:///d:/xampp/htdocs/StudentHub/api/index.php) providing instant live course search, notice filtering, and portal statistics without full page reloads. |
| **WDF 14** | **Secure File Uploads & HTTP Error Handling** | Secure coursework upload portal in [assignments.php](file:///d:/xampp/htdocs/StudentHub/assignments.php) with file size limits (5MB), MIME verification (PDF, DOC/DOCX, ZIP, TXT), collision-resistant hashing, unique submission constraints, and custom error pages ([403.php](file:///d:/xampp/htdocs/StudentHub/403.php), [404.php](file:///d:/xampp/htdocs/StudentHub/404.php)). |

---

## 5. Directory Structure

```text
StudentHub/
├── 403.php                      # Forbidden access error page (WDF Practical 14)
├── 404.php                      # Resource not found error page (WDF Practical 14)
├── announcements.php            # Campus circulars & notice board (WDF Practical 7, 13)
├── api/
│   └── index.php                # REST JSON API endpoint for AJAX queries (WDF Practical 13)
├── assignments.php              # Coursework upload & submission portal (WDF Practical 14)
├── courses.php                  # Course catalog with live AJAX search (WDF Practical 8, 13)
├── dashboard.php                # Student dashboard & academic summary (WDF Practical 7, 9)
├── events.php                   # Campus events directory & registration (WDF Practical 8)
├── index.php                    # Public landing page with feature cards (WDF Practical 1, 5)
├── login.php                    # Secure login portal with role dispatching (WDF Practical 9)
├── logout.php                   # Session termination & cookie cleanup (WDF Practical 10)
├── profile.php                  # Student profile viewer & contact updater (WDF Practical 3)
├── register-event.php           # Event registration handler (WDF Practical 8)
├── register.php                 # Student registration with validation & CSRF (WDF Practical 3, 10)
├── timetable.php                # Weekly timetable viewer by semester (WDF Practical 2)
│
├── admin/
│   ├── announcements.php        # Admin notice management (Create/Edit/Delete) (Stage 3)
│   ├── audit-logs.php           # Security & audit trail inspector (WDF Practical 2, 8)
│   ├── dashboard.php            # Administrator dashboard & quick stats (WDF Practical 7)
│   ├── events.php               # Campus event creation & management (WDF Practical 3, 8)
│   └── students.php             # Student directory, CRUD & CSV export (WDF Practical 11)
│
├── assets/
│   ├── css/style.css            # Central responsive design system & dark mode tokens
│   ├── js/script.js             # Mobile drawer, theme switcher & AJAX handlers (WDF 12, 13)
│   └── images/                  # Event posters, brand marks, and UI graphics
│
├── database/
│   ├── studenthub_dump_baseline.sql # Original baseline schema & verified records
│   └── seed_academic_data.sql       # Idempotent academic seed records (Courses, Timetable, etc.)
│
├── includes/
│   ├── auth.php                 # Session management, 30-min timeout, role guards (WDF 9, 10)
│   ├── csrf.php                 # Cryptographic Anti-CSRF token handling (WDF 10)
│   ├── db.php                   # PDO database connection & error handling (WDF 8)
│   ├── header.php               # Unified semantic navigation header (WDF 1, 7)
│   └── footer.php               # Unified semantic footer (WDF 1, 7)
│
├── tests/
│   └── run_tests.php            # Automated HTTP/CLI verification test suite
│
└── uploads/
    ├── assignments/             # Secure directory for student coursework uploads (.htaccess protected)
    └── posters/                 # Event image attachments
```

---

## 6. Verification & Automated Testing

You can run the built-in automated test suite at any time via web browser or command line:
```powershell
curl.exe http://localhost/StudentHub/tests/run_tests.php
```

All 9 test assertions verify:
1. Student authentication and session initialization.
2. Complete course retrieval from the database.
3. Weekly semester timetable retrieval.
4. Campus announcement querying and publisher relations.
5. Assignment deadlines and details.
6. Safe assignment document recording in `assignment_submissions`.
7. Duplicate submission prevention via `UNIQUE KEY` constraints.
8. Student profile update with contact data validation.
9. Audit trail logging in `audit_logs`.

---

## 7. College Demonstration Walkthrough & Examiner Guide

This step-by-step walkthrough is designed for presenting the StudentHub project during college practical examinations and viva assessments.

### Step 1: Start XAMPP Services
1. Open the **XAMPP Control Panel** (`xampp-control.exe`).
2. Click **Start** for **Apache** (verify port 80/443 highlights green).
3. Click **Start** for **MySQL** (verify port 3306 highlights green).

### Step 2: Open StudentHub in Browser
1. Open any modern browser (Google Chrome, Microsoft Edge, or Mozilla Firefox).
2. Navigate to:
   ```text
   http://localhost/StudentHub/
   ```
3. Highlight the public landing page, clean typography (DM Sans & Manrope), brand mark, and key feature highlights (**WDF Practical 1 & 5**).

### Step 3: Demonstrate Theme Switcher & Responsive Layout
1. Click the theme switcher button (**`◐`**) in the top header.
   - The entire website instantly transitions to a dark mode palette (`body.dark-theme`).
   - Refresh the page or navigate across different pages to demonstrate that the theme preference persists seamlessly via `localStorage` (**WDF Practical 4 & 12**).
2. Open Browser Developer Tools (`F12`) and toggle the **Device Toolbar** (`Ctrl + Shift + M`).
   - Switch to mobile viewports (e.g. 375px or 414px) to show how CSS Grid cards (`.events-grid`, `.form-grid`) collapse into clean single-column layouts (**WDF Practical 5**).
   - Click the hamburger button (`#menuToggle`) to demonstrate the animated mobile navigation drawer.

### Step 4: Demonstrate Student Registration, Login & Features
1. **Student Registration (`register.php` - WDF Practical 3, 9, 10):**
   - Click **Register ↗** in the header.
   - Enter your full name, college email address, academic course, study year, and password.
   - Point out client-side and server-side validation, Anti-CSRF token verification, BCRYPT password hashing, and automatic Student ID generation (`SH00000x`).
   - Submit the form and observe the success redirect to `login.php`.
2. **Student Login (`login.php`):**
   - Sign in using the registered credentials.
   - Show how the system regenerates session IDs to prevent session fixation, updates `last_login`, records a `USER_LOGIN` audit log, and routes students to `dashboard.php`.
3. **Student Dashboard (`dashboard.php`):**
   - Point out personalized welcome message, enrollment details, and live academic counter cards.
4. **Live Course Catalog (`courses.php` - WDF Practical 13):**
   - Click **Courses** in the navigation bar.
   - Type keywords (e.g. *"Data"*, *"Web"*) in the search input or select **Semester 3**.
   - Show how the catalog updates dynamically via asynchronous `fetch()` API calls to `api/index.php` without reloading the page.
5. **Weekly Timetable (`timetable.php` - WDF Practical 2):**
   - Click **Timetable** and view the structured schedule table for Semester 3 displaying time slots, course names, and room allocations.
6. **Campus Announcements (`announcements.php` - WDF Practical 7, 13):**
   - Click **Notices** to view published circulars.
   - Demonstrate category filtering (Academic, Examination, Campus) and real-time notice updates.
7. **Coursework Assignment Submission (`assignments.php` - WDF Practical 14):**
   - Click **Assignments** to view pending coursework tasks and deadlines.
   - In the submission card, select a file (PDF, DOC/DOCX, ZIP, or TXT &bull; max 5MB) and submit.
   - Show that the submission is verified, given a collision-resistant filename in `uploads/assignments/`, recorded in `assignment_submissions`, and badged with green **Submitted** status.
   - Explain the database `UNIQUE KEY (assignment_id, student_id)` constraint that prevents duplicate submissions.
8. **Campus Event Registration (`events.php`):**
   - Click **Events**, view upcoming events, and click **Register Now**.
   - Demonstrate that the registration is saved in `registrations` and duplicate registrations are blocked.
9. **Student Profile (`profile.php` - WDF Practical 3):**
   - Click **Profile**, view academic identity data, and update mobile number and gender to demonstrate immediate validation and persistence.

### Step 5: Demonstrate Access Control Guards & Inactivity Timeout
1. Log out using the **Log out** button in the header.
2. Attempt to directly access protected pages:
   - Navigate to `http://localhost/StudentHub/dashboard.php` -> show immediate redirect to `login.php` (**WDF Practical 9**).
   - Navigate to `http://localhost/StudentHub/403.php` -> show the custom **403 Access Denied** page (**WDF Practical 14**).
   - Navigate to a non-existent route `http://localhost/StudentHub/nonexistent.php` -> show the custom **404 Page Not Found** page (**WDF Practical 14**).
3. Explain the **30-minute inactivity session timeout** in `includes/auth.php` (**WDF Practical 10**), which clears idle sessions and redirects users with `login.php?timeout=1`.

### Step 6: Demonstrate Administrator Management Features
1. Log in with administrator privileges.
2. **Admin Overview (`admin/dashboard.php`):**
   - Show quick statistics (Total Students, Active Events, Registered Users) and quick management actions.
3. **Student Directory & CSV Export (`admin/students.php` - WDF Practical 11):**
   - View enrolled students with search and course filtering.
   - Click **Export CSV** to demonstrate dynamic server-side CSV streaming with `Content-Type: text/csv` and `Content-Disposition: attachment` headers.
4. **Campus Notices Management (`admin/announcements.php`):**
   - Publish a new campus notice, edit an existing notice, or delete one.
   - Show immediate reflection on the student notice board.
5. **Security Audit Trail (`admin/audit-logs.php`):**
   - Inspect the audit trail logging table.
   - Show records for logins, notice publications, student updates, and coursework submissions with timestamps and action badges.

### Step 7: Demonstrate REST JSON API Endpoints (WDF Practical 13)
Open a new browser tab to demonstrate the REST API:
- `http://localhost/StudentHub/api/index.php?action=stats` — Returns real-time portal metrics in JSON.
- `http://localhost/StudentHub/api/index.php?action=search_courses&q=data` — Returns matched courses in JSON.
- `http://localhost/StudentHub/api/index.php?action=invalid` — Demonstrates error handling returning HTTP 400 Bad Request with JSON error message.

---

## 8. Database Setup Requirements & Troubleshooting Guide

### Database Setup Requirements
- **Database Engine:** MySQL 8.0+ or MariaDB 10.4+ (default with XAMPP).
- **Default Database Name:** `studenthub`.
- **Default Credentials:** Host `localhost`, Username `root`, Password `""` (empty string).
- **Table Count:** 11 relational tables (`users`, `students`, `courses`, `timetable`, `announcements`, `assignments`, `assignment_submissions`, `events`, `registrations`, `remember_tokens`, `audit_logs`).

### Troubleshooting Guide

1. **Apache Port Conflict:**
   - *Symptom:* Apache fails to start in XAMPP due to port 80 or 443 conflict (e.g. IIS, Skype, or VMware).
   - *Fix:* In XAMPP Control Panel, click **Config** next to Apache &rarr; `httpd.conf` &rarr; change `Listen 80` to `Listen 8080`, and access the site at `http://localhost:8080/StudentHub/`.

2. **MySQL Connection Failed (`Database connection error`):**
   - *Symptom:* Website shows "Database connection error. Please try again later."
   - *Fix:* Ensure MySQL service is running in XAMPP. If your MySQL root user has a password, open `includes/db.php` and set `$password = 'your_mysql_password';`.

3. **Database Tables Missing or Empty:**
   - *Symptom:* Missing courses, timetable, or announcements.
   - *Fix:* Re-import the baseline dump and academic seed files using PowerShell or terminal:
     ```powershell
     Get-Content database/studenthub_dump_baseline.sql | & d:\xampp\mysql\bin\mysql.exe -u root studenthub
     Get-Content database/seed_academic_data.sql | & d:\xampp\mysql\bin\mysql.exe -u root studenthub
     ```
     Both scripts are 100% idempotent and will never duplicate existing data.

4. **File Upload Errors on Coursework Submissions:**
   - *Symptom:* "Upload directory does not exist or is not writable."
   - *Fix:* Verify that the folder `d:\xampp\htdocs\StudentHub\uploads\assignments\` exists and that Apache has write permissions.

5. **Demo User Login:**
   - *Symptom:* Existing account password is unknown.
   - *Fix:* You can safely register a new student account at any time via `register.php`. The registration automatically creates both the user account and the student academic profile.

