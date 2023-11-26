<?php

// Retrieve the user's email from the POST request.
$email = $_POST["email"];

// Generate a random token and hash it.
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);

// Set the expiry time for the token (30 minutes from now).
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// Include the database connection file.
$mysqli = require __DIR__ . "/database.php";

// SQL query to update the reset token and its expiry time in the database.
$sql = "UPDATE user
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

// Prepare and bind parameters to the SQL query.
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);

// Execute the query.
$stmt->execute();

// Check if the update was successful.
if ($mysqli->affected_rows) {

    $to = $email;
    $subject = 'Password Reset';

    // Start the message with a greeting and use HTML for formatting
    $message = <<<END
    <html>
    <head>
      <title>Password Reset</title>
    </head>
    <body>
      <p>Dear Customer,</p>
      <p>You have requested to reset your password. Please click on the link below to proceed:</p>
      <p><a href="https://ai.vitalpbx.cloud/reset-password.php?token=$token">Reset Password</a></p>
      <p>If you did not request a password reset, please ignore this message.</p>
      <p>Regards,<br>Your VitalPBX Agent AI Support</p>
    </body>
    </html>
    END;

    // Add headers for HTML format
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Additional From header
    $headers .= 'From: noreplay@vitalpbx.com' . "\r\n";

    mail($to, $subject, $message, $headers);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="chat-header">
        <div class="header-left"></div>
        <div class="header-title">VitalPBX Agent AI</div>
        <div class="header-right"></div>
    </div>
 
    <h1>Message sent, please check your inbox.</h1>

</body>
</html>
