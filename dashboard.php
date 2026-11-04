
<?php

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$student_name = $_SESSION['student_name'] ?? '';
$student_email = $_SESSION['student_email'] ?? '';

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

    <nav class="navbar">

        <div class="logo">
            SkillBloom
        </div>

        <div class="nav-links">

            <a href="dashboard.php">Home</a>

            <a href="profile.php">My Profile</a>

            <a href="my_skills.php">My Skills</a>

            <a href="find_skills.php">Find Skills</a>

            <a href="add_skill.php">Offer a Skill</a>

            <a href="requests.php">Requests</a>

            <a href="logout.php">Logout</a>

        </div>

    </nav>

    <div class="dashboard-container">

        <div class="welcome-box">

            <h1>
                Welcome, <?php echo htmlspecialchars($student_name, ENT_QUOTES, 'UTF-8'); ?>!
            </h1>

            <p>
                Welcome to your SkillBloom Dashboard.
            </p>

            <p class="email">
                <?php echo htmlspecialchars($student_email, ENT_QUOTES, 'UTF-8'); ?>
            </p>

        </div>

        <div class="dashboard-cards">

            <div class="dashboard-card">

                <h2>My Profile</h2>

                <p>
                    View and manage your personal information and skills.
                </p>

                <a href="profile.php">View Profile</a>

            </div>

            <div class="dashboard-card">

                <h2>Find Skills</h2>

                <p>Find students who can teach you useful skills.</p>

                <a href="find_skills.php">Find Skills</a>

            </div>

            <div class="dashboard-card">

                <h2>Offer a Skill</h2>

                <p>Share your skills and help other students.</p>

                <a href="add_skill.php">Offer Skill</a>

            </div>

            <div class="dashboard-card">

                <h2>Skill Requests</h2>

                <p>View and manage your skill exchange requests.</p>

                <a href="requests.php">View Requests</a>

            </div>

        </div>

    </div>

</div>

</body>

</html>
