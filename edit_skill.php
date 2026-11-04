
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];
$skill_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$skill_id || $skill_id <= 0) {
    header("Location: my_skills.php");
    exit;
}

$sql = "SELECT id, skill_name, category, skill_description
        FROM skills
        WHERE id = ? AND student_id = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ii", $skill_id, $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$skill = mysqli_fetch_assoc($result);

if (!$skill) {
    exit("Skill not found or you do not have permission to edit it.");
}

function safe($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Skill - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=8">
</head>

<body>

<div class="skills-page">
    <div class="skills-container">

        <div class="skills-header">
            <h1>Edit Skill</h1>
            <p>Update the details of your skill.</p>
        </div>

        <div class="skills-content">

            <form action="update_skill.php" method="post">

                <input type="hidden" name="id"
                       value="<?= (int)$skill['id'] ?>">

                <label class="profile-label">Skill Name</label>
                <input type="text" name="skill_name"
                       maxlength="100"
                       value="<?= safe($skill['skill_name']) ?>"
                       required>

                <label class="profile-label">Category</label>
                <input type="text" name="category"
                       maxlength="100"
                       value="<?= safe($skill['category']) ?>"
                       required>

                <label class="profile-label">Description</label>
                <textarea name="skill_description"
                          rows="5"><?= safe($skill['skill_description']) ?></textarea>

                <div class="profile-buttons">

                    <button type="submit" class="btn">
                        Update Skill
                    </button>

                    <a href="my_skills.php" class="profile-btn">
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>
</div>

</body>
</html>
