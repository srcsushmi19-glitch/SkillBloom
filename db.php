
<?php

$server = "localhost";
$db_username = "root";
$db_password = "";
$database = "campus_skill_exchange";

$con = mysqli_connect(
    $server,
    $db_username,
    $db_password,
    $database
);

if (!$con) {
    die("Database connection failed.");
}

mysqli_set_charset($con, "utf8mb4");

?>
