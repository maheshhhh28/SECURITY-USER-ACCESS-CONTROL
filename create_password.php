<?php
include 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if a password already exists for the given user_id
    $check_sql = "SELECT * FROM user_passwords WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Password exists, update it
        $update_sql = "UPDATE user_passwords SET password = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $password, $user_id);
        if ($update_stmt->execute()) {
            $message = "Password updated successfully. You can now <a href='index.php'>login</a>.";
        } else {
            $message = "Error: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        // No existing password, insert a new one
        $insert_sql = "INSERT INTO user_passwords (user_id, password) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("is", $user_id, $password);
        if ($insert_stmt->execute()) {
            $message = "Password creation successful. You can now <a href='index.php'>login</a>.";
        } else {
            $message = "Error: " . $insert_stmt->error;
        }
        $insert_stmt->close();
    }

    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Create Password</h1>
        <form method="POST" action="">
            <input type="hidden" name="user_id" value="<?php echo $_GET['user_id']; ?>">
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            
            <button type="submit">Create Password</button>
        </form>
        <div id="message"><?php echo $message; ?></div>
    </div>
</body>
</html>
