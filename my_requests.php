
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];

// Mark accepted requests as read
$read_sql = "UPDATE exchange_requests
             SET is_read = 1
             WHERE requester_id = ?
             AND status = 'accepted'
             AND is_read = 0";

$read_stmt = mysqli_prepare($con, $read_sql);

mysqli_stmt_bind_param($read_stmt, "i", $student_id);

mysqli_stmt_execute($read_stmt);

// Fetch requests sent by the logged-in student
$sql = "SELECT
            exchange_requests.id AS request_id,
            exchange_requests.request_message,
            exchange_requests.status,
            exchange_requests.created_at,
            skills.skill_name,
            skills.category,
            student.name AS owner_name,
            student.username AS owner_username
        FROM exchange_requests

        INNER JOIN skills
            ON exchange_requests.skill_id = skills.id

        INNER JOIN student
            ON skills.student_id = student.id

        WHERE exchange_requests.requester_id = ?

        ORDER BY exchange_requests.created_at DESC";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "i", $student_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

function safe($value) {
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Requests - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=12">
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="skills-page">

    <div class="skills-container">

        <div class="skills-header">
            <h1>My Skill Requests</h1>
            <p>Track the requests you have sent to other students.</p>
        </div>

        <div class="skills-content">

            <div class="find-skills-list">

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($request = mysqli_fetch_assoc($result)): ?>

                        <div class="find-skill-card">

                            <h3>
                                <?= safe($request['skill_name']) ?>
                            </h3>

                            <p>
                                <strong>Category:</strong>
                                <?= safe($request['category']) ?>
                            </p>

                            <p>
                                <strong>Skill Owner:</strong>
                                <?= safe($request['owner_name']) ?>
                            </p>

                            <p>
                                <strong>Owner Username:</strong>
                                <?= safe($request['owner_username']) ?>
                            </p>

                            <p>
                                <strong>My Message:</strong>
                                <?= $request['request_message'] !== ''
                                    ? nl2br(safe($request['request_message']))
                                    : 'No message provided.' ?>
                            </p>

                            <p>
                                <strong>Request Date:</strong>
                                <?= safe($request['created_at']) ?>
                            </p>

                            <p>
                                <strong>Status:</strong>
                                <strong>
                                    <?= ucfirst(safe($request['status'])) ?>
                                </strong>
                            </p>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p>You have not sent any skill requests yet.</p>

                <?php endif; ?>

            </div>

        </div>

        <div class="profile-buttons">

            <a href="dashboard.php" class="profile-btn">
                Back to Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>
