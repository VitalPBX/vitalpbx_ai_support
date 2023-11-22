<?php

// Define the database connection parameters.
$host = "localhost";         // The hostname of the database server.
$dbname = "vpbx_agentai";    // The name of the database.
$username = "";              // The username for the database connection.
$password = "";              // The password for the database connection.

// Create a new instance of the mysqli class to establish a database connection.
$mysqli = new mysqli(
    hostname: $host,     // Pass the hostname.
    username: $username, // Pass the username.
    password: $password, // Pass the password.
    database: $dbname    // Pass the database name.
);
                     
// Check if the connection was successful.
if ($mysqli->connect_errno) {
    // If there is a connection error, terminate the script and display an error message.
    die("Connection error: " . $mysqli->connect_error);
}

// Return the mysqli object representing the connection.
return $mysqli;
