
<?php

session_start();

// Check login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
require "db.php";

// Get logged-in student ID
$id = $_SESSION['student_id'];

// Fetch existing student information
$sql = "SELECT name, username, age, gender, email, phone, description
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

// Safe output function
function safe($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profile - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=8">
</head>

<body>

<div class="profile-page">

    <div class="profile-container">

        <div class="profile-header">

            <h1>Edit Profile</h1>

            <p>Update your personal information</p>

        </div>

        <div class="profile-content">
            
        <?php if (isset($_GET['error'])): ?>

    <p class="error-message">

        <?php
        if ($_GET['error'] === 'email') {
            echo "This email is already used by another student.";
        } else {
            echo "Please enter valid information in all fields.";
        }
        ?>

    </p>

<?php endif; ?>

            <form action="update_profile.php" method="post">

                <div class="profile-grid">

                    <!-- Full Name -->
                    <div class="profile-field">

                        <label class="profile-label">Full Name</label>

                        <input
                            type="text"
                            name="name"
                            value="<?= safe($student['name']) ?>"
                            required
                        >

                    </div>

                    <!-- Username -->
                    <div class="profile-field">

                        <label class="profile-label">Username</label>

                        <input
                            type="text"
                            value="<?= safe($student['username']) ?>"
                            readonly
                        >

                    </div>

                    <!-- Age -->
                    <div class="profile-field">

                        <label class="profile-label">Age</label>

                        <input
                            type="number"
                            name="age"
                            value="<?= safe($student['age']) ?>"
                            min="1"
                            required
                        >

                    </div>

                    <!-- Gender -->
                    <div class="profile-field">

                        <label class="profile-label">Gender</label>

                        <input
                            type="text"
                            name="gender"
                            value="<?= safe($student['gender']) ?>"
                            required
                        >

                    </div>

                    <!-- Email -->
                    <div class="profile-field">

                        <label class="profile-label">Email Address</label>

                        <input
                            type="email"
                            name="email"
                            value="<?= safe($student['email']) ?>"
                            required
                        >

                    </div>

                    <!-- Phone -->
                    <div class="profile-field">

                        <label class="profile-label">Phone Number</label>

                        <input
                            type="tel"
                            name="phone"
                            value="<?= safe($student['phone']) ?>"
                            required
                        >

                    </div>

                </div>

                <!-- About Me -->
                <div class="profile-about">

                    <h3>About Me</h3>

                    <textarea
                        name="description"
                        rows="5"
                        required
                    ><?= safe($student['description']) ?></textarea>

                </div>

                <div class="profile-buttons">

                    <button type="submit" class="btn">
                        Update Profile
                    </button>

                    <a href="profile.php" class="profile-btn">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
