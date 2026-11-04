
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

$skill_name = trim($_POST['skill_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$skill_description = trim($_POST['skill_description'] ?? '');

// Validate input
if (
    !$skill_id ||
    $skill_id <= 0 ||
    $skill_name === '' ||
    $category === '' ||
    strlen($skill_name) > 100 ||
    strlen($category) > 100
) {
    header("Location: my_skills.php?error=invalid");
    exit;
}

// Update only the logged-in student's skill
$sql = "UPDATE skills
        SET skill_name = ?,
            category = ?,
            skill_description = ?
        WHERE id = ? AND student_id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssii",
    $skill_name,
    $category,
    $skill_description,
    $skill_id,
    $student_id
);

if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) >= 0) {
        header("Location: my_skills.php?updated=1");
        exit;
    }

} else {
    header("Location: my_skills.php?error=update");
    exit;
}

mysqli_stmt_close($stmt);
?>
