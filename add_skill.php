
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $skill_name = trim($_POST['skill_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['skill_description'] ?? '');

    if ($skill_name === '' || $category === '') {
        $error = "Skill name and category are required.";
    } else {

        $sql = "INSERT INTO skills
                (student_id, skill_name, category, skill_description)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "isss",
            $_SESSION['student_id'],
            $skill_name,
            $category,
            $description
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: my_skills.php");
            exit;
        }

        $error = "Could not save the skill.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Skill - Skillora</title>
    <link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<div class="login-container">

    <h1>Add a Skill</h1>

    <?php if ($error !== ''): ?>
        <p class="error-message">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="post">

        <input
            type="text"
            name="skill_name"
            placeholder="Skill name"
            maxlength="100"
            required
        >

        <input
            type="text"
            name="category"
            placeholder="Category"
            maxlength="100"
            required
        >

        <textarea
            name="skill_description"
            placeholder="Describe your skill"
        ></textarea>

        <button class="btn" type="submit">Save Skill</button>

    </form>

    <p><a href="my_skills.php">Back to My Skills</a></p>

</div>

</body>
</html>
