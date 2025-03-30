<?php
session_start();
require_once 'db_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    } else {
        try {
            if ($role === 'admin') {
                $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
            } elseif ($role === 'student') {
                $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
            } else {
                $error = "Invalid role selected!";
                exit();
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                if (password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    
                    // Set session variable based on role.
                    if ($role === 'student') {
                        $_SESSION['student_id'] = $user['id'];
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                    }
                    $_SESSION['role'] = $role;
                    $_SESSION['email'] = $email;

                    if ($role === 'admin') {
                        header("Location: admin_dashboard.php");
                    } elseif ($role === 'student') {
                        header("Location: student_dashboard.php");
                    }
                    exit();
                } else {
                    $error = "Invalid email or password!";
                }
            } else {
                $error = "User not found!";
            }

            $stmt->close();
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        .error { color: red; margin-bottom: 10px; }
        .login-form { display: flex; flex-direction: column; }
        .login-form input, .login-form select { margin-bottom: 10px; padding: 8px; }
        .login-form button { padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
    <form class="login-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
