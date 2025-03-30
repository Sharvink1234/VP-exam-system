<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Online Exam System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard - Online Exam System</h1>
        <nav>
            <ul>
                <li><a href="add_subject.php">Add Subject</a></li>
                <li><a href="add_question.php">Add Question Bank</a></li>
                <li><a href="view_results.php">View Results</a></li>
                <li><a href="meeting_schedule.php">Manage Meetings</a></li>
                <li><a href="notice_board.php">Notice Board</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h2>Welcome, Admin!</h2>
        <p>Manage subjects, question banks, results, meetings, and notices from the dashboard.</p>
    </section>

    <footer>
        <p>&copy; 2025 Online Exam System. All Rights Reserved.</p>
    </footer>
</body>
</html>
