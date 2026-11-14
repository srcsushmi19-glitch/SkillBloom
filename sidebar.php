
<?php
require_once "notification.php";
?>

<!-- Sidebar Toggle Button -->
<button type="button" class="sidebar-toggle" id="sidebarToggle">
    ☰
</button>

<!-- SkillBloom Sidebar -->
<aside class="main-sidebar" id="mainSidebar">

    <div class="sidebar-logo">
        <h2>SkillBloom</h2>
    </div>

    <nav class="sidebar-menu">

        <a href="dashboard.php">
            <span>⌂</span>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="profile.php">
            <span>👤</span>
            <span class="menu-text">My Profile</span>
        </a>

        <a href="my_skills.php">
            <span>📚</span>
            <span class="menu-text">My Skills</span>
        </a>

        <a href="find_skills.php">
            <span>🔍</span>
            <span class="menu-text">Find Skills</span>
        </a>

        <a href="add_skill.php">
            <span>＋</span>
            <span class="menu-text">Offer a Skill</span>
        </a>

        <a href="requests.php" class="sidebar-notification-link">
            <span>📩</span>
            <span class="menu-text">Requests</span>

            <?php if ($pending_requests > 0): ?>
                <span class="notification-badge">
                    <?= $pending_requests ?>
                </span>
            <?php endif; ?>
        </a>

        <a href="my_requests.php" class="sidebar-notification-link">
            <span>📤</span>
            <span class="menu-text">My Requests</span>

            <?php if ($accepted_requests > 0): ?>
                <span class="notification-badge">
                    <?= $accepted_requests ?>
                </span>
            <?php endif; ?>
        </a>

    </nav>

    <div class="sidebar-logout">
        <a href="logout.php">
            <span>↪</span>
            <span class="menu-text">Logout</span>
        </a>
    </div>

</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleButton = document.getElementById("sidebarToggle");

    toggleButton.addEventListener("click", function () {
        document.body.classList.toggle("sidebar-collapsed");
    });

});
</script>
