
<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

require "db.php";

$student_id = $_SESSION['student_id'];

// Search and category filters
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// Get available categories
$category_sql = "SELECT DISTINCT category
                 FROM skills
                 WHERE student_id != ?
                 ORDER BY category ASC";

$category_stmt = mysqli_prepare($con, $category_sql);
mysqli_stmt_bind_param($category_stmt, "i", $student_id);
mysqli_stmt_execute($category_stmt);

$category_result = mysqli_stmt_get_result($category_stmt);

$categories = [];

while ($row = mysqli_fetch_assoc($category_result)) {
    $categories[] = $row['category'];
}

// Fetch skills
$sql = "SELECT skills.id,
               skills.skill_name,
               skills.category,
               skills.skill_description,
               student.name AS student_name
        FROM skills
        INNER JOIN student
        ON skills.student_id = student.id
        WHERE skills.student_id != ?";

$params = [$student_id];
$types = "i";

if ($search !== '') {
    $sql .= " AND (skills.skill_name LIKE ?
               OR student.name LIKE ?)";

    $search_value = "%" . $search . "%";

    $params[] = $search_value;
    $params[] = $search_value;

    $types .= "ss";
}

if ($category !== '') {
    $sql .= " AND skills.category = ?";

    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY skills.created_at DESC";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, $types, ...$params);

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

    <title>Find Skills - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=9">
</head>

<body>

<?php include "sidebar.php"; ?>

<div class="skills-page">

    <div class="skills-container">

        <div class="skills-header">

            <h1>Find Skills</h1>

            <p>Explore skills offered by other students.</p>

        </div>

        <div class="skills-content">

    <?php if (isset($_GET['request'])): ?>

        <?php if ($_GET['request'] === 'success'): ?>

    <div class="success-message">
        
    Skill request sent successfully!
    
</div>

        <?php elseif ($_GET['request'] === 'updated'): ?>

    <div class="success-message">
        
    Your pending request has been updated successfully!
    
</div>

                
    <?php elseif ($_GET['request'] === 'duplicate'): ?>

        <div class="error-message">
    
    You have already sent a pending or accepted request for this skill.
                    
</div>

    <?php elseif ($_GET['request'] === 'invalid'): ?>

         <div class="error-message">
                
         Invalid skill request. You cannot request your own skill.
                    
        
</div>

    <?php elseif ($_GET['request'] === 'error'): ?>

        <div class="error-message">
            
        Unable to send your request. Please try again.
                    
</div>

        <?php endif; ?>

    <?php endif; ?>

            <form method="GET" action="find_skills.php" class="find-skills-form">

                <input
                    type="text"
                    name="search"
                    placeholder="Search by skill or student name..."
                    value="<?= safe($search) ?>"
                >

                <select name="category">

                    <option value="">All Categories</option>

                    <?php foreach ($categories as $cat): ?>

                        <option
                            value="<?= safe($cat) ?>"
                            <?= ($category === $cat) ? 'selected' : '' ?>
                        >
                            <?= safe($cat) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <button type="submit" class="btn">
                    Search
                </button>

                <a href="find_skills.php" class="profile-btn">
                    Clear Filters
                </a>

            </form>

            <div class="find-skills-list">

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($skill = mysqli_fetch_assoc($result)): ?>

                        <div class="find-skill-card">

                            <h3><?= safe($skill['skill_name']) ?></h3>

                            <p>
                                <strong>Category:</strong>
                                <?= safe($skill['category']) ?>
                            </p>

                            <p>
                                <strong>Description:</strong>
                                <?= safe($skill['skill_description']) ?>
                            </p>

                            <p>
                                <strong>Offered by:</strong>
                                <?= safe($skill['student_name']) ?>
                            </p>

                            <form action="send_request.php"
                                  method="POST"
                                  class="skill-request-form">

                                <input type="hidden"
                                       name="skill_id"
                                       value="<?= (int)$skill['id'] ?>">

                                <textarea
                                    name="request_message"
                                    rows="3"
                                    placeholder="Write a message to the skill owner (optional)..."
                                ></textarea>

                                <button type="submit" class="btn">
                                    Send Request
                                </button>

                            </form>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p>No skills found.</p>

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
