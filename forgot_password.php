<?php

require('config.inc.php');
include('header.html');
require('mysql.inc.php');

// For storing errors:
$pass_errors = array();

// If it's a POST request, handle the form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	// Validate the email address:
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
	{
		// Check for the existence of that email address...
		$q = 'SELECT id FROM users WHERE email="' .  
		    mysqli_real_escape_string($dbc, $_POST['email']) . '"';
		
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_num_rows($r) == 1) 
		{ // Retrieve the user ID:
		    list($uid) = mysqli_fetch_array($r, MYSQLI_NUM); 
		} 
		else 
		{ // No database match made.
		    $pass_errors['email'] = 'The email address does not exist.';
		}
		
	} else 
	{ // No valid address submitted.
	    $pass_errors['email'] = 'Please enter a valid email address.';
	} // End of $_POST['email'] if.
	
	if (empty($pass_errors)) 
	{ // If everything's OK.
		// Create a new, random password:
		$p = generate_random_password(); 

		// Update the database:
		$q = "UPDATE users SET pass='" .  get_password_hash($p) . "' WHERE id=$uid";
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_affected_rows($dbc) == 1) 
		{ // If it ran OK.
			// Send an email:
			$body = "Your password to log into the Online Bookstore website has been temporarily changed to '$p'. Please log in and then change your password.";
			mail ($_POST['email'], 'Your new password', $body, 'From: no-reply@comp.polyu.edu.hk');
			
			echo '<h3>Your password has been changed successfully.</h3>';
			echo '<p>Your new password has been sent to you as an email message.  ' .  
				 'Please log in and then change your password.</p>';
			include('footer.html');
			exit(); // Stop the script.
		} else 
		{ // If it did not run OK.	
			echo 'Your password could not be changed due to a system error.'; 
		}
	} // End of $uid if.
}

if (!isset($pass_errors)) 
    $pass_errors = array();

require('forms.inc.php');

echo '<div>';
echo '<h4>Reset Your Password</h4>';
echo '</div>';
echo '<form action="forgot_password.php" method="post">';

echo '<p>Email Address<br>';

create_form_input('email', 'text', $pass_errors);

echo '</p><input type="submit" value="Reset">';
echo '</form>';
?>
