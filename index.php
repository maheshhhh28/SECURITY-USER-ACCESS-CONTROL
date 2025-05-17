<?php
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $login_status = "";

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $role = $user['role'];

        // Check the password
        $password_sql = "SELECT password FROM user_passwords WHERE user_id = ?";
        $password_stmt = $conn->prepare($password_sql);
        $password_stmt->bind_param("i", $user_id);
        $password_stmt->execute();
        $password_result = $password_stmt->get_result();

        if ($password_result->num_rows > 0) {
            $password_data = $password_result->fetch_assoc();
            if (password_verify($password, $password_data['password'])) {
                // Log the login attempt
                $login_status = "success";
                $log_sql = "INSERT INTO login (username, password, login_time, status) VALUES (?, ?, NOW(), ?)";
                $log_stmt = $conn->prepare($log_sql);
                $hashed_password = $password_data['password'];
                $log_stmt->bind_param("sss", $username, $hashed_password, $login_status);
                $log_stmt->execute();
                $log_stmt->close();

                // Redirect based on role
                if ($role == 'dean') {
                    header("Location: welcome_dean.php?user_id=$user_id");
                } elseif ($role == 'faculty') {
                    header("Location: welcome_faculty.php?user_id=$user_id");
                } elseif ($role == 'student') {
                    header("Location: welcome_student.php?user_id=$user_id");
                }
                exit();
            } else {
                $message = "Invalid username or password";
                $login_status = "failed";
            }
        } else {
            $message = "Invalid username or password";
            $login_status = "failed";
        }

        $password_stmt->close();
    } else {
        $message = "Invalid username or password";
        $login_status = "failed";
    }

    // Log the login attempt
    $log_sql = "INSERT INTO login (username, password, login_time, status) VALUES (?, ?, NOW(), ?)";
    $log_stmt = $conn->prepare($log_sql);
    $empty_password = ''; // Assign empty password to a variable
    $log_stmt->bind_param("sss", $username, $empty_password, $login_status);
    $log_stmt->execute();
    $log_stmt->close();

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <button type="submit">Login</button>
        </form>
        <button onclick="window.location.href='register.php'">Register</button>
        <button onclick="window.location.href='forgot_password.php'">Forgot Password</button>
        <div id="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
