<?php
include 'database.php';

$user_id = $_GET['user_id'];
$marks_data = [];

// Fetch marks data for the student
$sql = "SELECT u.username, m.subject, m.marks FROM marks m JOIN users u ON m.student_id = u.id WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $marks_data[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Student</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Student</h1>
        <h2>Your Marks</h2>
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
    </div>
</body>
</html>
