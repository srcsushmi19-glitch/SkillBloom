
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];

// Fetch requests received for the logged-in student's skills
$sql = "SELECT
            exchange_requests.id AS request_id,
            exchange_requests.request_message,
            exchange_requests.status,
            exchange_requests.created_at,
            skills.skill_name,
            skills.category,
            student.name AS requester_name,
            student.username AS requester_username
        FROM exchange_requests

        INNER JOIN skills
            ON exchange_requests.skill_id = skills.id

        INNER JOIN student
            ON exchange_requests.requester_id = student.id

        WHERE skills.student_id = ?

        ORDER BY
            CASE
                WHEN exchange_requests.status = 'pending' THEN 0
                ELSE 1
            END,
            exchange_requests.created_at DESC";

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

    <title>Skill Requests - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=11">
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="skills-page">

    <div class="skills-container">

        <div class="skills-header">
            <h1>Received Skill Requests</h1>
            <p>Manage requests from other students.</p>
        </div>

        <div class="skills-content">

        
<?php if (isset($_GET['updated'])): ?>

    <?php if ($_GET['updated'] === 'accepted'): ?>
        <div class="success-message">
            Skill request accepted successfully!
        </div>

    <?php elseif ($_GET['updated'] === 'rejected'): ?>
        <div class="success-message">
            Skill request rejected successfully!
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error-message">
        <?php
        if ($_GET['error'] === 'invalid') {
            echo "Invalid request action.";
        } elseif ($_GET['error'] === 'notfound') {
            echo "Request not found or already processed.";
        } else {
            echo "Unable to update request. Please try again.";
        }
        ?>
    </div>
<?php endif; ?>


            <div class="find-skills-list">

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($request = mysqli_fetch_assoc($result)): ?>

                        <div class="find-skill-card">

                            <h3><?= safe($request['skill_name']) ?></h3>

                            <p>
                                <strong>Category:</strong>
                                <?= safe($request['category']) ?>
                            </p>

                            <p>
                                <strong>Requested By:</strong>
                                <?= safe($request['requester_name']) ?>
                            </p>

                            <p>
                                <strong>Username:</strong>
                                <?= safe($request['requester_username']) ?>
                            </p>

                            <p>
                                <strong>Message:</strong>
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

                                <?= ucfirst(safe($request['status'])) ?>

                            </p>

                            
<?php if ($request['status'] === 'pending'): ?>

    <div class="request-action-buttons">

        <form action="update_request.php" method="POST">

            <input type="hidden" name="request_id"
                   value="<?= (int)$request['request_id'] ?>">

            <input type="hidden" name="action"
                   value="accepted">

            <button type="submit" class="accept-btn">
                Accept
            </button>

        </form>

        <form action="update_request.php" method="POST"
              onsubmit="return confirm('Are you sure you want to reject this request?');">

            <input type="hidden" name="request_id"
                   value="<?= (int)$request['request_id'] ?>">

            <input type="hidden" name="action"
                   value="rejected">

            <button type="submit" class="reject-btn">
                Reject
            </button>

        </form>

    </div>

<?php endif; ?>


                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p>No skill requests received yet.</p>

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
