<?php
require('config.inc.php');
// The config file also starts the session.

// If the user isn't logged in, redirect them:
redirect_invalid_user();

// Destroy the session:
$_SESSION = array(); // Destroy the variables.
session_destroy(); // Destroy the session itself.
setcookie(session_name(), '', time() - 300); // Destroy the cookie.

include('header.html');

// Print a customized message:
echo '<h3>Logged Out</h3><p>Thank you for visiting. You are now logged out. Please come back soon!</p>';

// Include the HTML footer:
include ('footer.html');
?>