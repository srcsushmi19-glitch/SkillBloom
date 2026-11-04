
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

function safe($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Skills - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=7">
</head>

<body>

<div class="skills-page">

    <div class="skills-container">
        
<?php if (isset($_GET['updated']) && $_GET['updated'] == '1'): ?>
    <div class="success-message">
        Skill updated successfully!
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="error-message">
        <?php
        if ($_GET['error'] === 'invalid') {
            echo "Please enter valid skill information.";
        } else {
            echo "Unable to update skill. Please try again.";
        }
        ?>
    </div>
<?php endif; ?>


        <div class="skills-header">

            <h1>My Skills</h1>

            <p>
                Manage the skills you want to share with others.
            </p>

            <a href="add_skill.php" class="skills-add-btn">
                + Add New Skill
            </a>

        </div>

        <div class="skills-content">

            <?php if (mysqli_num_rows($result) > 0): ?>

                <div class="skills-grid">

                    <?php while ($skill = mysqli_fetch_assoc($result)): ?>

                        <div class="skill-item">

                            <h2>
                                <?= safe($skill['skill_name']) ?>
                            </h2>

                            <span class="skill-category">
                                <?= safe($skill['category']) ?>
                            </span>

                            <p class="skill-description">
                                <?= nl2br(safe($skill['skill_description'])) ?>
                            </p>

                            <div class="skill-actions">

                                <a href="edit_skill.php?id=<?= (int)$skill['id'] ?>"
                                   class="skill-edit-btn">
                                    Edit
                                </a>

                                <form action="delete_skill.php"
                                      method="post"
                                      class="skill-delete-form"
                                      onsubmit="return confirm('Delete this skill?');">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= (int)$skill['id'] ?>">

                                    <button type="submit" class="skill-delete-btn">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="skills-empty">

                    <h2>No Skills Added Yet</h2>

                    <p>
                        Start sharing your knowledge by adding your first skill.
                    </p>

                    <a href="add_skill.php" class="skills-add-btn">
                        Add Your First Skill
                    </a>

                </div>

            <?php endif; ?>

            <div class="skills-back">

                <a href="dashboard.php" class="profile-btn">
                    Back to Dashboard
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>
