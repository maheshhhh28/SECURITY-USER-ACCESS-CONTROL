<?php
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, email, mobile, gender) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $mobile, $gender);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        $access_sql = "INSERT INTO user_access (username, role) VALUES (?, ?)";
        $access_stmt = $conn->prepare($access_sql);
        $access_stmt->bind_param("ss", $username, $role);
        $access_stmt->execute();
        $access_stmt->close();

        header("Location: create_password.php?user_id=" . $user_id);
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="mobile">Mobile Number:</label>
            <input type="tel" id="mobile" name="mobile" required><br>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select><br>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="">Select</option>
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
                <option value="dean">Dean</option>
            </select><br>

            <button type="submit">Register</button>
        </form>
        <button onclick="window.location.href='index.php'">Go to Login</button>
        <div id="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
