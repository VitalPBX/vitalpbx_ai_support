<?php
// A flag to track invalid login attempts.
$is_invalid = false;
$not_confirmed = false; // Flag for unconfirmed accounts

// Check if the form was submitted (POST request).
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Include the database connection file and get the mysqli object.
    $mysqli = require __DIR__ . "/database.php";
    
    // Prepare an SQL query to select the user by email. The email is escaped to prevent SQL injection.
    $sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_POST["email"]));
    
    // Execute the query and get the result.
    $result = $mysqli->query($sql);
    
    // Fetch the user data from the result.
    $user = $result->fetch_assoc();
    
    // Check if user exists.
    if ($user) {
        // Check if the account is confirmed.
        if ($user['is_confirmed'] == 0) {
            $not_confirmed = true;
        } else {
            // Check if the password is correct.
            if (password_verify($_POST["password"], $user["password_hash"])) {
                
                // Start a new session and regenerate the session ID for security.
                session_start();
                session_regenerate_id();
                
                // Store the user's ID in the session.
                $_SESSION["user_id"] = $user["id"];
                
                // Redirect to the agentai page.
                header("Location: agentai.php");
                exit;
            }
        }
    }
    
    // Set the invalid flag to true if login fails.
    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="chat-header">
        <div class="header-left"></div>
        <div class="header-title">VitalPBX Agent AI</div>
        <div class="header-right"></div>
    </div>

    <h1>Login</h1>
    
    <!-- Display an error message if the login is invalid -->
    <?php if ($is_invalid): ?>
        <h1>Invalid login</h1>
    <?php endif; ?>

    <!-- Display a message if the account is not confirmed -->
    <?php if ($not_confirmed): ?>
        <h1>Your account has not been confirmed. Please check your email.</h1>
    <?php endif; ?>
    
    <!-- Login form -->
    <form method="post">
        <!-- Email input field -->
        <label for="email">Email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <!-- Password input field -->
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <!-- Submit button -->
        <button>Log in</button>
    </form>

    <!-- Link to a password recovery page -->
    <div class="body-2"><a href="forgot-password.php">Forgot password?</a></div>

    <!-- Link to a Sing Up page -->
    <div class="body-2"><a href="signup.html">sign up</a></div>
    

</body>
</html>
