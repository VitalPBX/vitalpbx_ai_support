<?php

// Start or resume a session.
session_start();

// Check if there is a user_id stored in the session.
if (isset($_SESSION["user_id"])) {
    
    // Include the database connection file and get the mysqli object.
    $mysqli = require __DIR__ . "/database.php";
    
    // Prepare an SQL query to select the user by their ID stored in the session.
    $sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
            
    // Execute the query and get the result.
    $result = $mysqli->query($sql);
    
    // Fetch the user data from the result.
    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    
    <h1>Home</h1>
    
    <!-- Conditional content based on user login status -->
    <?php if (isset($user)): ?>
        
        <?php header('Location: agentai.php'); ?>
        <?php exit; ?>
       
    <?php else: ?>
        
        <!-- Provide options to log in or sign up if the user is not logged in -->
        <?php header('Location: login.php'); ?>
        
    <?php endif; ?>
    
</body>
</html>
