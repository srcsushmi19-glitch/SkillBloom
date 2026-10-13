
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$id = $_SESSION['student_id'];

$sql = "SELECT name, username, age, gender, email,
               phone, description
        FROM student
        WHERE id = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    exit("Student profile not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Skillora</title>
    <link rel="stylesheet" href="style.css?v=5">
</head>

<body>

<div class="login-container">

    <h1>My Profile</h1>

    <p><strong>Name:</strong>
        <?= htmlspecialchars($student['name']) ?>
    </p>

    <p><strong>Username:</strong>
        <?= htmlspecialchars($student['username']) ?>
    </p>

    <p><strong>Age:</strong>
        <?= htmlspecialchars((string)$student['age']) ?>
    </p>

    <p><strong>Gender:</strong>
        <?= htmlspecialchars($student['gender']) ?>
    </p>

    <p><strong>Email:</strong>
        <?= htmlspecialchars($student['email']) ?>
    </p>

    <p><strong>Phone:</strong>
        <?= htmlspecialchars($student['phone']) ?>
    </p>

    <p><strong>About Me:</strong>
        <?= htmlspecialchars($student['description']) ?>
    </p>

    <p>
        <a href="dashboard.php">Back to Dashboard</a>
    </p>

</div>

</body>
</html>
