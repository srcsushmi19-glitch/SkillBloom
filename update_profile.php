<?php

session_start();

// Check login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: profile.php");
    exit;
}

// Database connection
require "db.php";

// Get logged-in student ID
$id = $_SESSION['student_id'];

// Get updated form data
$name = trim($_POST['name'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$gender = trim($_POST['gender'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$description = trim($_POST['description'] ?? '');

// Validate required fields
if (
    $name === '' ||
    $age <= 0 ||
    $gender === '' ||
    $email === '' ||
    $phone === '' ||
    $description === ''
) {
    header("Location: edit_profile.php?error=invalid");
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: edit_profile.php?error=invalid");
    exit;
}

// Check whether another student already uses this email
$check_sql = "SELECT id FROM student WHERE email = ? AND id != ?";

$check_stmt = mysqli_prepare($con, $check_sql);

mysqli_stmt_bind_param($check_stmt, "si", $email, $id);
mysqli_stmt_execute($check_stmt);

$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    header("Location: edit_profile.php?error=email");
    exit;
}

mysqli_stmt_close($check_stmt);

// Update student information
$sql = "UPDATE student
        SET name = ?,
            age = ?,
            gender = ?,
            email = ?,
            phone = ?,
            description = ?
        WHERE id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sissssi",
    $name,
    $age,
    $gender,
    $email,
    $phone,
    $description,
    $id
);

// Execute update
if (mysqli_stmt_execute($stmt)) {

    // Update session information
    $_SESSION['student_name'] = $name;
    $_SESSION['student_email'] = $email;

    // Redirect to profile page
    header("Location: profile.php?updated=1");
    exit;

} else {

    echo "Error updating profile: " . htmlspecialchars(mysqli_stmt_error($stmt));

}

mysqli_stmt_close($stmt);
mysqli_close($con);

?>