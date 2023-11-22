<?php

// Retrieve the reset token from the query string.
$token = $_GET["token"];

// Hash the token using SHA256 for secure comparison.
$token_hash = hash("sha256", $token);

// Include the database connection file.
$mysqli = require __DIR__ . "/database.php";

// SQL query to find a user with the given reset token hash.
$sql = "SELECT * FROM user WHERE reset_token_hash = ?";

// Prepare the SQL query.
$stmt = $mysqli->prepare($sql);

// Bind the hashed token parameter to the prepared statement.
$stmt->bind_param("s", $token_hash);

// Execute the query.
$stmt->execute();

// Retrieve the result set from the executed statement.
$result = $stmt->get_result();

// Fetch the user associated with the reset token.
$user = $result->fetch_assoc();

// Check if no user is found with the provided token.
if ($user === null) {
    die("token not found");
}

// Check if the token has expired.
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title> <!-- Page Title -->
    <meta charset="UTF-8"> <!-- Character Encoding Declaration -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <h1>Reset Password</h1> <!-- Page Heading -->

    <!-- Form for submitting new password -->
    <form method="post" action="process-reset-password.php">
        <!-- Hidden field to carry the token -->
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <!-- New Password Input Field -->
        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <!-- Password Confirmation Field -->
        <label for="password_confirmation">Repeat password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation">

        <!-- Submit Button -->
        <button>Send</button>
    </form>

</body>
</html>
