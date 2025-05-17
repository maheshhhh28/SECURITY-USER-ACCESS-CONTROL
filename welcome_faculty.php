<?php
include 'database.php';

$user_id = $_GET['user_id'];
$students_list = [];

// Fetch the list of students assigned to this faculty
$sql = "SELECT s.id, s.username FROM users s JOIN faculty_students fs ON s.id = fs.student_id WHERE fs.faculty_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
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
    <title>Welcome Faculty</title>
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
        <h1>Welcome Faculty</h1>
        <h2>Your Students</h2>
        <?php if (!empty($students_list)) { ?>
            <ul>
                <?php foreach ($students_list as $student) { ?>
                    <li><a href="view_student_marks.php?student_id=<?php echo $student['id']; ?>" class="student-link"><?php echo $student['username']; ?></a></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No students found.</p>
        <?php } ?>
    </div>
</body>
</html>
