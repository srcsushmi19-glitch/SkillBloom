<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $server = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "campus_skill_exchange";


    // =========================
    // Database Connection
    // =========================

    $con = mysqli_connect(
        $server,
        $db_username,
        $db_password,
        $database
    );

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }


    // =========================
    // Get Form Data
    // =========================

    $name = $_POST['name'];
    $user_username = $_POST['username'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $user_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];


    // =========================
    // Check Password Length
    // =========================

    if (strlen($user_password) < 8) {

        echo "ERROR: Password must be at least 8 characters long.";
        exit;
    }


    // =========================
    // Check Password Match
    // =========================

    if ($user_password !== $confirm_password) {

        echo "ERROR: Passwords do not match.";
        exit;
    }


    // =========================
    // Check Username
    // =========================

    $check_username_sql =
        "SELECT id FROM student WHERE username = ?";

    $check_username_stmt =
        mysqli_prepare($con, $check_username_sql);

    if (!$check_username_stmt) {

        die("ERROR: Could not prepare username check.");
    }

    mysqli_stmt_bind_param(
        $check_username_stmt,
        "s",
        $user_username
    );

    mysqli_stmt_execute($check_username_stmt);

    $username_result =
        mysqli_stmt_get_result($check_username_stmt);


    if (mysqli_num_rows($username_result) > 0) {

        echo "ERROR: Username already exists. Please choose another username.";
        exit;
    }

    mysqli_stmt_close($check_username_stmt);


    // =========================
    // Check Email
    // =========================

    $check_email_sql =
        "SELECT id FROM student WHERE email = ?";

    $check_email_stmt =
        mysqli_prepare($con, $check_email_sql);

    if (!$check_email_stmt) {

        die("ERROR: Could not prepare email check.");
    }

    mysqli_stmt_bind_param(
        $check_email_stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($check_email_stmt);

    $email_result =
        mysqli_stmt_get_result($check_email_stmt);


    if (mysqli_num_rows($email_result) > 0) {

        echo "ERROR: Email already taken. Please use another email address.";
        exit;
    }

    mysqli_stmt_close($check_email_stmt);


    // =========================
    // Hash Password
    // =========================

    $hashed_password =
        password_hash(
            $user_password,
            PASSWORD_DEFAULT
        );


    // =========================
    // Insert Student Data
    // =========================

    $sql = "INSERT INTO student
            (
                name,
                username,
                age,
                gender,
                email,
                password,
                phone,
                description,
                `date`
            )
            VALUES
            (
                ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP()
            )";


    // =========================
    // Prepare Insert Statement
    // =========================

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {

        die("ERROR: Could not prepare the SQL statement.");
    }


    // =========================
    // Bind Parameters
    // =========================

    mysqli_stmt_bind_param(
        $stmt,
        "ssisssss",
        $name,
        $user_username,
        $age,
        $gender,
        $email,
        $hashed_password,
        $phone,
        $description
    );


    // =========================
    // Execute Registration
    // =========================

    if (mysqli_stmt_execute($stmt)) {

        // Registration successful
        $_SESSION['registration_success'] = true;

        // Go to Login Page
        header("Location: login.php");
        exit;

    } else {

        echo "ERROR: " . mysqli_stmt_error($stmt);
    }


    // =========================
    // Close Statement
    // =========================

    mysqli_stmt_close($stmt);


    // =========================
    // Close Database
    // =========================

    mysqli_close($con);

} else {

    // If someone directly opens process.php
    header("Location: index.php");
    exit;
}

?>