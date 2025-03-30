<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "online_exam_system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student results
$sql = "SELECT students.name, results.subject_id, subjects.subject_name, results.marks, results.total_marks 
        FROM results 
        JOIN students ON results.student_id = students.id 
        JOIN subjects ON results.subject_id = subjects.id";

$results = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Results - Online Exam System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>View Results - Online Exam System</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="add_subject.php">Add Subject</a></li>
                <li><a href="add_question.php">Add Question Bank</a></li>
                <li><a href="meeting_schedule.php">Manage Meetings</a></li>
                <li><a href="notice_board.php">Notice Board</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="content">
        <h2>Exam Results</h2>
        <table border="1">
            <tr>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Marks</th>
                <th>Total Marks</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['subject_name']; ?></td>
                    <td><?php echo $row['marks']; ?></td>
                    <td><?php echo $row['total_marks']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </section>

    <footer>
        <p>&copy; 2025 Online Exam System. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
