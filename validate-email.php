<?php

// Include the database connection file.
$mysqli = require __DIR__ . "/database.php";

// Prepare an SQL query to check if the email exists in the 'user' table.
// The email is retrieved from the GET request and escaped to prevent SQL injection.
$sql = sprintf("SELECT * FROM user WHERE email = '%s'", $mysqli->real_escape_string($_GET["email"]));
                
// Execute the query and store the result.
$result = $mysqli->query($sql);

// Determine if the email is available (i.e., not found in the database).
$is_available = $result->num_rows === 0;

// Set the content type of the response to JSON.
header("Content-Type: application/json");

// Encode the availability status as a JSON object and output it.
echo json_encode(["available" => $is_available]);
