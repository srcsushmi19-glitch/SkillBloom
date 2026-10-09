<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_name = $_SESSION['student_name'];
$student_email = $_SESSION['student_email'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=4">

</head>

<body>

<div class="dashboard-page">

    <!-- Navigation Bar -->
    <nav class="navbar">

        <div class="logo">
            SkillBloom
        </div>

        <div class="nav-links">

            <a href="dashboard.php">Home</a>

            <a href="#">My Profile</a>

            <a href="#">Find Skills</a>

            <a href="#">Offer a Skill</a>

            <a href="#">Requests</a>

            <a href="logout.php">Logout</a>

        </div>

    </nav>


    <!-- Dashboard Content -->
    <div class="dashboard-container">

        <div class="welcome-box">

            <h1>
                Welcome, <?php echo htmlspecialchars($student_name); ?>!
            </h1>

            <p>
                Welcome to your SkillBloom Dashboard.
            </p>

            <p class="email">
                <?php echo htmlspecialchars($student_email); ?>
            </p>

        </div>


        <!-- Dashboard Cards -->
        <div class="dashboard-cards">


            <div class="dashboard-card">

                <h2>My Profile</h2>

                <p>
                    View and manage your personal information and skills.
                </p>

                <a href="#">
                    View Profile
                </a>

            </div>


            <div class="dashboard-card">

                <h2>Find Skills</h2>

                <p>
                    Find students who can teach you useful skills.
                </p>

                <a href="#">
                    Find Skills
                </a>

            </div>


            <div class="dashboard-card">

                <h2>Offer a Skill</h2>

                <p>
                    Share your skills and help other students.
                </p>

                <a href="#">
                    Offer Skill
                </a>

            </div>


            <div class="dashboard-card">

                <h2>Skill Requests</h2>

                <p>
                    View and manage your skill exchange requests.
                </p>

                <a href="#">
                    View Requests
                </a>

            </div>


        </div>

    </div>

</div>

</body>

</html>