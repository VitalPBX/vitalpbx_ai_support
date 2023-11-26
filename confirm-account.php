<?php
// Retrieve the confirmation token from the query string.
$token = $_GET["token"];

// Hash the token using SHA256 for secure comparison.
$token_hash = hash("sha256", $token);

// Include the database connection file.
$mysqli = require __DIR__ . "/database.php";

// SQL query to find a user with the given confirmation token hash.
// Asegúrate de que estés utilizando el nombre de campo correcto.
$sql = "SELECT * FROM user WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Error in SQL query: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

// Actualiza el campo correcto para confirmar la cuenta.
$update_sql = "UPDATE user SET is_confirmed = 1 WHERE reset_token_hash = ?";
$update_stmt = $mysqli->prepare($update_sql);

if (!$update_stmt) {
    die("Error in SQL query: " . $mysqli->error);
}

$update_stmt->bind_param("s", $token_hash);
if (!$update_stmt->execute()) {
    die("Error updating record: " . $mysqli->error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Confirmation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="chat-header">
        <div class="header-left"></div>
        <div class="header-title">VitalPBX Agent AI</div>
        <div class="header-right"></div>
    </div>

    <h1>Account Confirmation</h1>

    <h1>Your account has been successfully confirmed. You can now <a href="login.php">log in</a>.</h1>

</body>
</html>
