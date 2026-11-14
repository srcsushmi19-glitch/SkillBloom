<?php

$student_id = $_SESSION['student_id'];

// Count pending received requests
$received_sql = "SELECT COUNT(*) AS total
                 FROM exchange_requests
                 INNER JOIN skills
                 ON exchange_requests.skill_id = skills.id
                 WHERE skills.student_id = ?
                 AND exchange_requests.status = 'pending'";

$received_stmt = mysqli_prepare($con, $received_sql);
mysqli_stmt_bind_param($received_stmt, "i", $student_id);
mysqli_stmt_execute($received_stmt);

$received_result = mysqli_stmt_get_result($received_stmt);
$received_data = mysqli_fetch_assoc($received_result);

$pending_requests = (int)$received_data['total'];


// Count accepted sent requests
$sent_sql = "SELECT COUNT(*) AS total
             FROM exchange_requests
             WHERE requester_id = ?
             AND status = 'accepted'
             AND is_read = 0";

$sent_stmt = mysqli_prepare($con, $sent_sql);
mysqli_stmt_bind_param($sent_stmt, "i", $student_id);
mysqli_stmt_execute($sent_stmt);

$sent_result = mysqli_stmt_get_result($sent_stmt);
$sent_data = mysqli_fetch_assoc($sent_result);

$accepted_requests = (int)$sent_data['total'];

?>