<?php

// The base URL:
DEFINE ('BASE_URL', 'your_website_address');
// Is the website live?
$live = true;

// Start the session:
session_start();

// A function for handling errors.
// Takes five arguments: error number, error message (string), name of the file where the error occurred (string) 
// line number where the error occurred, and the variables that existed at the time (array).
// Returns true.
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) 
{
	// Need these two vars:
	global $live;
	
	// Build the error message:
	$message = "An error occurred in script '$e_file' on line $e_line:\n$e_message\n";
	
	// Add the backtrace:
	$message .= "<pre>" .print_r(debug_backtrace(), 1) . "</pre>\n";
    $contact_email = 'kwoksinman1@gmail.com';
	
	if (!$live) 
	{ // Show the error in the browser.	
		echo '<div class="error">' . nl2br($message) . '</div>';

	} 
	else 
	{ // Development (print the error).

		// Send the error in an email:
		error_log ($message, 1, $contact_email, 'From:admin@example.com');
		
		// Only print an error message in the browser, if the error isn't a notice:
		if ($e_number != E_NOTICE) 
		{
			echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div>';
		}

	} // End of $live IF-ELSE.
	
	return true; // So that PHP doesn't try to handle the error, too.

} // End of my_error_handler() definition.

// Use my error handler:
set_error_handler('my_error_handler');


// This function redirects invalid users.
// It takes two arguments: 
// - The session element to check
// - The destination to where the user will be redirected. 
function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://') 
{	
	// Check for the session item:
	if (!isset($_SESSION[$check])) 
	{
		$url = $protocol . BASE_URL . $destination; // Define the URL.
		header("Location: $url");
		exit(); // Quit the script.
	}
	
} // End of redirect_invalid_user() function.

function generate_random_password()
{
    return substr(md5(uniqid(rand(), true)), 15, 15);
}

// Omit the closing PHP tag to avoid 'headers already sent' errors!
