<?php

// Check if the 'name' field is empty. If it is, terminate the script and output an error message.
if (empty($_POST["name"])) {
    die("Name is required");
}

// Validate the email using PHP's built-in filter. If it's not a valid email, terminate the script with an error message.
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Check the length of the password. If it's less than 8 characters, terminate with an error message.
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

// Ensure that the password contains at least one letter. If not, terminate with an error message.
if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

// Ensure the password contains at least one number. If not, terminate with an error message.
if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

// Check if the password and its confirmation match. If not, terminate with an error message.
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Hash the password using PHP's password_hash function.
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Include the database connection file and store the returned mysqli object.
$mysqli = require __DIR__ . "/database.php";

// Generate a random token and hash it.
$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);

// Set the expiry time for the token (30 minutes from now).
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

// SQL query to insert a new user into the 'user' table.
$sql = "INSERT INTO user (name, email, password_hash, reset_token_hash, reset_token_expires_at) VALUES (?, ?, ?, ?, ?)";

// Initialize a new statement object.
$stmt = $mysqli->stmt_init();

// Prepare the SQL statement. If there's an error, terminate with an SQL error message.
if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Bind the user input to the prepared statement.
$stmt->bind_param("sssss", $_POST["name"], $_POST["email"], $password_hash, $token_hash, $expiry);


// Execute the statement. If successful, redirect to the success page. Otherwise, handle errors.
if ($stmt->execute()) {

    // Check if the update was successful.
    if ($mysqli->affected_rows) {

        $to = $_POST["email"];
        $subject = 'Account Confirmation';

        // Start the message with a greeting and use HTML for formatting
        $message = <<<END
        <html>
        <head>
          <title>Account Confirmation</title>
        </head>
        <body>
          <p>Dear Customer,</p>
          <p>Thank you for registering with us. Please click on the link below to confirm your account:</p>
          <p><a href="https://ai.vitalpbx.cloud/confirm-account.php?token=$token">Confirm Account</a></p>
          <p>If you did not create an account, please ignore this message.</p>
          <p>Regards,<br>Your Support Team</p>
        </body>
        </html>
        END;

        // Add headers for HTML format
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // Additional From header
        $headers .= 'From: noreply@yourwebsite.com' . "\r\n";

        mail($to, $subject, $message, $headers);
    }

} else {
    // Check for a duplicate email error (MySQL error code 1062).
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        // For other SQL errors, output the error details.
        die($mysqli->error . " " . $mysqli->errno);
    }
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
 
    <h1>Your account has been created but you must confirm. We have sent you a message to your email.</h1>

</body>
</html>
