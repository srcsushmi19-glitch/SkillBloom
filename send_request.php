
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: find_skills.php");
    exit;
}

require "db.php";

$requester_id = $_SESSION['student_id'];

$skill_id = filter_input(INPUT_POST, 'skill_id', FILTER_VALIDATE_INT);
$request_message = trim($_POST['request_message'] ?? '');

if (!$skill_id || $skill_id <= 0) {
    header("Location: find_skills.php?request=invalid");
    exit;
}

// Check whether the skill exists and belongs to another student
$check_sql = "SELECT id
              FROM skills
              WHERE id = ? AND student_id != ?";

$check_stmt = mysqli_prepare($con, $check_sql);

mysqli_stmt_bind_param(
    $check_stmt,
    "ii",
    $skill_id,
    $requester_id
);

mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) === 0) {
    header("Location: find_skills.php?request=invalid");
    exit;
}


// Check whether a pending request already exists
$pending_sql = "SELECT id
                FROM exchange_requests
                WHERE skill_id = ?
                AND requester_id = ?
                AND status = 'pending'
                ORDER BY created_at DESC
                LIMIT 1";

$pending_stmt = mysqli_prepare($con, $pending_sql);

mysqli_stmt_bind_param(
    $pending_stmt,
    "ii",
    $skill_id,
    $requester_id
);

mysqli_stmt_execute($pending_stmt);

$pending_result = mysqli_stmt_get_result($pending_stmt);


// If pending request exists, update it
if (mysqli_num_rows($pending_result) > 0) {

    $pending_data = mysqli_fetch_assoc($pending_result);

    $request_id = $pending_data['id'];

    $update_sql = "UPDATE exchange_requests
                   SET request_message = ?,
                       created_at = CURRENT_TIMESTAMP
                   WHERE id = ?
                   AND status = 'pending'";

    $update_stmt = mysqli_prepare($con, $update_sql);

    mysqli_stmt_bind_param(
        $update_stmt,
        "si",
        $request_message,
        $request_id
    );

    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: find_skills.php?request=updated");
        exit;
    } else {
        header("Location: find_skills.php?request=error");
        exit;
    }

}


// If no pending request exists, insert a new request
$insert_sql = "INSERT INTO exchange_requests
               (skill_id, requester_id, request_message)
               VALUES (?, ?, ?)";

$insert_stmt = mysqli_prepare($con, $insert_sql);

mysqli_stmt_bind_param(
    $insert_stmt,
    "iis",
    $skill_id,
    $requester_id,
    $request_message
);

if (mysqli_stmt_execute($insert_stmt)) {
    header("Location: find_skills.php?request=success");
    exit;
} else {
    header("Location: find_skills.php?request=error");
    exit;
}

?>
