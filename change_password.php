<?php

// This page is used to change an existing password.
// Users must be logged in to access this page.

require ('config.inc.php');

// If the user isn't logged in, redirect them:
redirect_invalid_user();

// Include the header file:
include ('header.html');
require ('mysql.inc.php');

// For storing errors:
$pass_errors = array();

// If it's a POST request, handle the form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{			
	// Check for the existing password:
	if (!empty($_POST['current'])) 
	{
		$current = mysqli_real_escape_string($dbc, $_POST['current']);
	} 
	else 
	{
		$pass_errors['current'] = 'Please enter your current password.';
	}
	
	// Check for a password and match against the confirmed password:
	
	if ($_POST['pass1'] == $_POST['pass2']) 
	{
		$p = mysqli_real_escape_string($dbc, $_POST['pass1']);
	} 
	else 
	{
		$pass_errors['pass2'] = 'Your new passwords do not match.';
	}
	
	if (empty($pass_errors)) 
	{
		$q = "SELECT id FROM users WHERE pass='"  .  get_password_hash($current) .  "' AND id={$_SESSION['user_id']}";	
		$r = mysqli_query($dbc, $q);

		if (mysqli_num_rows($r) == 1) 
		{ 
			$q = "UPDATE users SET pass='"  .  get_password_hash($p) .  "' WHERE id={$_SESSION['user_id']}";

			if ($r = mysqli_query ($dbc, $q)) 
			{ 
				echo '<h3>Your password has been changed successfully.</h3>';
				include ('footer.html');
				exit();

			} 
			else 
			{
				trigger_error('Your password could not be changed due to a system error.'); 
			}
		} 
		else 
		{	
			$pass_errors['current'] = 'Your current password is incorrect.';			
		} 
	}	
}

require('forms.inc.php');
?>

<h3>Change Your Password</h3>

<form action="change_password.php" method="post">

<p><label for="current"><strong>Current Password</strong></label><br>
<?php create_form_input('current', 'password', $pass_errors); ?></p>

<p><label for="pass1"><strong>New Password</strong></label><br>
<?php create_form_input('pass1', 'password', $pass_errors); ?></p>

<p><label for="pass2"><strong>Confirm New Password</strong></label><br>
<?php create_form_input('pass2', 'password', $pass_errors); ?></p>

<input type="submit" name="submit_button" value="Change" id="submit_button">
</form>

<?php
include('footer.html');
?>