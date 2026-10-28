# StudentHub — Test & Security Report

## 1. Project Overview

StudentHub is a student management and academic portal developed using HTML, CSS, JavaScript, PHP, and MySQL. It provides student-facing academic features and an administration interface.

## 2. Functional Testing

The following features were manually tested in the local XAMPP environment.

| Test | Result |
|---|---|
| Website opens through localhost | PASS |
| Student login and dashboard | PASS |
| Administrator login and dashboard | PASS |
| Courses page | PASS |
| Timetable page | PASS |
| Assignments page | PASS |
| Events and event registration | PASS |
| Announcements page | PASS |
| Student profile page | PASS |
| Navigation between pages | PASS |
| Light/dark theme | PASS |
| Responsive layout | PASS |

*Note: These results reflect the manual tests performed. Any feature not actually tested should be marked NOT TESTED.*

## 3. Database Integrity

The database used by the project is named `studenthub`.

The database was exported using phpMyAdmin. The project verification process reported that existing records were preserved and no duplicate records were identified.

The SQL database dump is provided separately as `studenthub.sql`.

## 4. Security Review

The following security controls should be checked in the source code and verified before being marked as passed.

- **Password storage:** Verify that passwords are hashed and checked securely.
- **SQL injection protection:** Verify that prepared statements are used for queries containing user input.
- **Authentication:** Verify that protected pages require a valid login.
- **Authorization:** Verify that administrator-only operations cannot be accessed by students.
- **Input validation:** Verify that form inputs are validated on the server.
- **Output escaping:** Verify that user-supplied text is safely escaped before being displayed.
- **File uploads:** If assignment uploads are enabled, verify file type, size, storage, and access restrictions.
- **Session protection:** Review session handling and logout behaviour.
- **Database credentials:** Ensure credentials and other secrets are not exposed in public source code.

These checks are a review checklist, not proof that a formal security audit has passed. Record the actual result of each check after inspecting and testing it.

## 5. User Interface and Accessibility

The website was manually checked for general usability, page navigation, visual consistency, and light/dark theme behaviour.

Responsive behaviour was checked on the available test device. Additional device and accessibility testing should be recorded separately if performed.

## 6. Test Environment

- Operating system: Windows
- Local server: XAMPP Apache
- Database server: MySQL
- Database administration: phpMyAdmin
- Browser: Microsoft Edge
- Application URL: `http://localhost/StudentHub/`

## 7. Conclusion

StudentHub was manually tested in the local environment, and the main student and administrator features were reported to be working. The database export was prepared for submission. Security controls should be confirmed against the implementation before this report is treated as a completed security audit.