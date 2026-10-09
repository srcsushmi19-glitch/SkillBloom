<?php 
session_start(); 
 
$show_success = false; 
 
if (isset($_SESSION['registration_success'])) { 
    $show_success = true; 
 
    // Show the message only once 
    unset($_SESSION['registration_success']); 
} 
?> 
 
<!DOCTYPE html> 
<html lang="en"> 
 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
 
    <title>Campus Skill Exchange</title> 
 
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700&family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS --> 
    <link rel="stylesheet" href="style.css?v=3"> 
</head> 
 
<body> 
 
    <div class="page"> 
 
        <div class="container"> 
 
            <h1>Welcome to SkillBloom</h1> 
 
            <p class="intro-text"> 
                Learn. Share. Level Up. 
            </p> 
 
            <?php if ($show_success): ?> 
 
                <div class="success-message"> 
                    Thanks for Registration! 
                    
                </div> 

                
 
            <?php endif; ?> 
 
            <!-- Registration Form --> 
            <form action="process.php" method="post"> 
 
                <input  
                    type="text"  
                    name="name"  
                    placeholder="Enter your name"  
                    required
                > 
 
                <input  
                    type="text"  
                    name="username"  
                    placeholder="Enter your username"  
                    required
                > 
 
                <input  
                    type="number"  
                    name="age"  
                    placeholder="Enter your age"  
                    required
                > 
 
                <input  
                    type="text"  
                    name="gender"  
                    placeholder="Enter your gender"  
                    required
                > 
 
                <input  
                    type="email"  
                    name="email"  
                    placeholder="Enter your email"  
                    required
                > 
 
                <input  
                    type="password"  
                    name="password"  
                    placeholder="Enter your password"  
                    required
                > 
 
                <input  
                    type="password"  
                    name="confirm_password"  
                    placeholder="Confirm your password"  
                    required
                > 
 
                <input  
                    type="tel"  
                    name="phone"  
                    placeholder="Enter your phone number"  
                    required
                > 
 
                <textarea  
                    name="description" 
                    placeholder="Tell us about yourself, your skills, and what you want to learn..." 
                    required
                ></textarea> 
 
                <!-- Sign In option --> 
                <p class="signin-option"> 
                    Already have an account? 
                    <a href="login.php">Sign In</a> 
                </p> 
 
                <button class="btn" type="submit">Submit</button> 
 
            </form> 
 
        </div> 
 
    </div> 
 
</body> 
 
</html>