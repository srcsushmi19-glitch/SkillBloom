<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SkillBloom</title>

    <link rel="stylesheet" href="style.css?v=3">
</head>

<body>

<div class="page">

    <div class="login-container">

        <h1>Welcome to SkillBloom</h1>

        <p class="login-text">
            Login to continue to your dashboard
        </p>

        <form action="login_process.php" method="post">

            <input
                type="text"
                name="username"
                placeholder="Enter your username"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button class="btn" type="submit">
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>