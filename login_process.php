<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $server = "localhost";
    $db_username = "root";
    $db_password = "";
    $database = "campus_skill_exchange";

    // Database connection
    $con = mysqli_connect(
        $server,
        $db_username,
        $db_password,
        $database
    );

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get login data
    $user_username = $_POST['username'];
    $user_password = $_POST['password'];

    // Find student by username
    $sql = "SELECT * FROM student WHERE username = ?";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $user_username
        );

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        // Check username
        if (mysqli_num_rows($result) == 1) {

            $student = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($user_password, $student['password'])) {

                // Store student information in session
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['student_username'] = $student['username'];
                $_SESSION['student_email'] = $student['email'];

                // Login successful
                header("Location: dashboard.php");
                exit;

            } else {

                echo "ERROR: Incorrect password.";

            }

        } else {

            echo "ERROR: Username not found.";

        }

        mysqli_stmt_close($stmt);

    } else {

        echo "ERROR: Could not prepare the SQL statement.";

    }

    mysqli_close($con);

} else {

    // Direct access protection
    header("Location: login.php");
    exit;

}

?>