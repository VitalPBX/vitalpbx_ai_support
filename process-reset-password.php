<?php
// Retrieve the reset token sent via POST request.
$token = $_POST["token"];

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

// Password validation checks.
if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}
if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}
if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// Hash the new password.
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// SQL query to update the user's password, and reset the token fields.
$sql = "UPDATE user
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

// Prepare the SQL query.
$stmt = $mysqli->prepare($sql);

// Bind parameters to the prepared statement.
$stmt->bind_param("ss", $password_hash, $user["id"]);

// Execute the query.
$stmt->execute();

// Notify the user that the password has been updated.
// Redirect to the agentai page.
header("Location: login.php");
exit;
?>
