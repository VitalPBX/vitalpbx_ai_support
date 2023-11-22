<?php

// Start or resume the current session.
session_start();

// Destroy the current session, effectively logging out the user.
session_destroy();

// Redirect the user to the 'index.php' page.
header("Location: index.php");

// Terminate the script to ensure no further code is executed after the redirection.
exit;
