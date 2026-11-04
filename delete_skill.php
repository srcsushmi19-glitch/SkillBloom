
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: my_skills.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];
$skill_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$skill_id || $skill_id <= 0) {
    header("Location: my_skills.php?error=delete");
    exit;
}

// Delete only the logged-in student's skill
$sql = "DELETE FROM skills
        WHERE id = ? AND student_id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $skill_id,
    $student_id
);

if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        header("Location: my_skills.php?deleted=1");
        exit;
    } else {
        header("Location: my_skills.php?error=delete");
        exit;
    }

} else {
    header("Location: my_skills.php?error=delete");
    exit;
}

mysqli_stmt_close($stmt);
?>
