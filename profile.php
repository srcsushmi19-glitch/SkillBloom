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

function safe($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$initial = strtoupper(substr($student['name'], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=6">
</head>

<body>

<div class="profile-page">

    <div class="profile-container">

    <?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>

    <div class="success-message">
        Profile updated successfully!
    </div>

<?php endif; ?>

        <div class="profile-header">

            <div class="profile-avatar">
                <?= safe($initial) ?>
            </div>

            <h1>My Profile</h1>

            <h2><?= safe($student['name']) ?></h2>

            <p class="profile-username">
                @<?= safe($student['username']) ?>
            </p>

        </div>

        <div class="profile-content">

            <h3>Personal Information</h3>

            <div class="profile-grid">

                <div class="profile-field">
                    <span class="profile-label">Full Name</span>
                    <span class="profile-value">
                        <?= safe($student['name']) ?>
                    </span>
                </div>

                <div class="profile-field">
                    <span class="profile-label">Username</span>
                    <span class="profile-value">
                        <?= safe($student['username']) ?>
                    </span>
                </div>

                <div class="profile-field">
                    <span class="profile-label">Age</span>
                    <span class="profile-value">
                        <?= safe($student['age']) ?>
                    </span>
                </div>

                <div class="profile-field">
                    <span class="profile-label">Gender</span>
                    <span class="profile-value">
                        <?= safe($student['gender']) ?>
                    </span>
                </div>

                <div class="profile-field">
                    <span class="profile-label">Email Address</span>
                    <span class="profile-value">
                        <?= safe($student['email']) ?>
                    </span>
                </div>

                <div class="profile-field">
                    <span class="profile-label">Phone Number</span>
                    <span class="profile-value">
                        <?= safe($student['phone']) ?>
                    </span>
                </div>

            </div>

            <div class="profile-about">

                <h3>About Me</h3>

                <p>
                    <?= nl2br(safe($student['description'])) ?>
                </p>

            </div>

            <div class="profile-buttons">

    <a href="edit_profile.php" class="profile-btn">
        Edit Profile
    </a>

    <a href="dashboard.php" class="profile-btn">
        Back to Dashboard
    </a>

</div>

        </div>

    </div>

</div>

</body>
</html>