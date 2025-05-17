<?php
include 'database.php';

$user_id = $_GET['user_id'];
$students_list = [];

// Fetch the list of all students
$sql = "SELECT id, username FROM users WHERE role = 'student'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $students_list[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Dean</title>
    <style>
        body {
            background-color: #ffffff;
            color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            text-align: center;
            width: 80%;
            margin: 0 auto;
        }
        .student-link {
            color: #007bff;
            text-decoration: none;
        }
        .student-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Dean</h1>
        <h2>All Students</h2>
        <?php if (!empty($students_list)) { ?>
            <ul>
                <?php foreach ($students_list as $student) { ?>
                    <li><a href="view_student_marks.php?student_id=<?php echo $student['id']; ?>" class="student-link"><?php echo htmlspecialchars($student['username']); ?></a></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No students found.</p>
        <?php } ?>
    </div>
</body>
</html>
