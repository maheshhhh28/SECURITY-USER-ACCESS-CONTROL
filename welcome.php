<?php
include 'database.php';

$role = $_GET['role'];
$color = $_GET['color'];
$user_id = $_GET['user_id'];

$students_list = [];

// Fetch students list for Dean
$sql = "SELECT id, username FROM users WHERE role = 'student'";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $students_list[] = $row;
}
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
                    <li><a href="welcome.php?role=dean&color=white&user_id=<?php echo $user_id; ?>&student_id=<?php echo $student['id']; ?>" class="student-link"><?php echo $student['username']; ?></a></li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No students found.</p>
        <?php } ?>
        
        <?php
        if (isset($_GET['student_id'])) {
            $student_id = $_GET['student_id'];
            $marks_data = [];

            // Fetch marks data for the selected student
            $sql = "SELECT u.username, m.subject, m.marks FROM marks m JOIN users u ON m.student_id = u.id WHERE u.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $marks_data[] = $row;
            }
            $stmt->close();
        ?>
            <h2>Marks for <?php echo htmlspecialchars($marks_data[0]['username']); ?></h2>
            <?php if (!empty($marks_data)) { ?>
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                    <?php foreach ($marks_data as $mark) { ?>
                        <tr>
                            <td><?php echo $mark['subject']; ?></td>
                            <td><?php echo $mark['marks']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>No marks data available.</p>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>
