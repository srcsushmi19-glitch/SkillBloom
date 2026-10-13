
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$id = $_SESSION['student_id'];

$sql = "SELECT id, skill_name, category, skill_description
        FROM skills
        WHERE student_id = ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Skills - Skillora</title>
    <link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<div class="login-container">

    <h1>My Skills</h1>

    <p>
        <a href="add_skill.php">+ Add New Skill</a>
    </p>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($skill = mysqli_fetch_assoc($result)): ?>

            <div class="dashboard-card">

                <h2>
                    <?= htmlspecialchars($skill['skill_name']) ?>
                </h2>

                <p>
                    Category:
                    <?= htmlspecialchars($skill['category']) ?>
                </p>

                <p>
                    <?= htmlspecialchars(
                        $skill['skill_description'] ?? ''
                    ) ?>
                </p>

                <a href="edit_skill.php?id=<?= (int)$skill['id'] ?>">
                    Edit
                </a>

                <a href="delete_skill.php?id=<?= (int)$skill['id'] ?>"
                   onclick="return confirm('Delete this skill?');">
                    Delete
                </a>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>You have not added any skills yet.</p>

    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
