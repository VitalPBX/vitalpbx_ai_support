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

// SQL query to insert a new user into the 'user' table.
$sql = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";

// Initialize a new statement object.
$stmt = $mysqli->stmt_init();

// Prepare the SQL statement. If there's an error, terminate with an SQL error message.
if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

// Bind the user input to the prepared statement.
$stmt->bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

// Execute the statement. If successful, redirect to the success page. Otherwise, handle errors.
if ($stmt->execute()) {
    header("Location: signup-success.html");
    exit;
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
