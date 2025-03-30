<?php
session_start();
include 'db_connection.php'; // Database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Input validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prevent SQL Injection using Prepared Statements
        // Admin Login Check
        $stmt_admin = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt_admin->bind_param("s", $email);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        // Student Login Check
        $stmt_student = $conn->prepare("SELECT * FROM students WHERE email = ?");
        $stmt_student->bind_param("s", $email);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();

        // Check Admin Login
        if ($result_admin->num_rows > 0) {
            $admin = $result_admin->fetch_assoc();
            // Basic password check (recommend using password_hash and password_verify in production)
            if ($password === $admin['password']) {
                $_SESSION['admin'] = true;
                $_SESSION['role'] = 'admin';
                header("Location: admin_dashboard.php");
                exit();
            }
        }

        // Check Student Login
        if ($result_student->num_rows > 0) {
            $student = $result_student->fetch_assoc();
            // Basic password check (recommend using password_hash and password_verify in production)
            if ($password === $student['password']) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['role'] = 'student';
                header("Location: student_dashboard.php");
                exit();
            }
        }

        // If no match found
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Online Exam System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="flex h-screen items-center justify-center bg-gray-100">
        <div class="bg-white shadow-xl rounded-2xl p-8 max-w-sm w-full">
            <h2 class="text-2xl font-bold text-center mb-4">Login</h2>
            <?php 
            if (isset($error)) {
                echo "<p class='text-red-500 text-center'>$error</p>";
            }
            ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">Email:</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                           class="w-full p-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium">Password:</label>
                    <input type="password" id="password" name="password" required
                           class="w-full p-2 border rounded-md">
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600">Login</button>
            </form>
        </div>
    </div>
</body>
</html>