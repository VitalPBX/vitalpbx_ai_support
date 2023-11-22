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

    // Include the mailer configuration file.
    $mail = require __DIR__ . "/mailer.php";

    // Configure the email settings.
    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
    Click <a href="http://example.com/reset-password.php?token=$token">here</a> 
    to reset your password.
    END;

    // Attempt to send the email.
    try {
        $mail->send();
    } catch (Exception $e) {
        // Output an error message if the email fails to send.
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}

// Notify the user that the message has been sent.
echo "Message sent, please check your inbox.";
