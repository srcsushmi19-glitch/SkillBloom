
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: requests.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];

$request_id = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$request_id || $request_id <= 0 ||
    !in_array($action, ['accepted', 'rejected'], true)) {
    header("Location: requests.php?error=invalid");
    exit;
}

// Update only pending requests belonging to the logged-in skill owner
$sql = "UPDATE exchange_requests
        INNER JOIN skills
            ON exchange_requests.skill_id = skills.id
        SET exchange_requests.status = ?
        WHERE exchange_requests.id = ?
        AND skills.student_id = ?
        AND exchange_requests.status = 'pending'";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sii",
    $action,
    $request_id,
    $student_id
);

if (mysqli_stmt_execute($stmt)) {

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        header("Location: requests.php?updated=" . $action);
        exit;
    } else {
        header("Location: requests.php?error=notfound");
        exit;
    }

} else {
    header("Location: requests.php?error=update");
    exit;
}
?>
