<?php
  require('config.inc.php');
  require('mysql.inc.php');
  
  // If it's a POST request, handle the login attempt:
  if ($_SERVER['REQUEST_METHOD'] == 'POST') 
  {
	  // Array for recording errors:
	  $login_errors = array();

	  // Validate the email address:
	  if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
	  {
	  	$e = mysqli_real_escape_string($dbc, $_POST['email']);
	  } 
	  else 
	  {
	  	$login_errors['email'] = 'Please enter a valid email address.';
	  }

	  // Make sure that the password is not empty:
	  if (!empty($_POST['pass'])) 
	  {
	  	$p = mysqli_real_escape_string($dbc, $_POST['pass']);
	  } 
	  else
	  {
	  	$login_errors['pass'] = 'Please enter your password.';
	  }
	
	  if (empty($login_errors)) 
	  { // OK to proceed.

	  	// Query the database:
	  	$q = "SELECT id, first_name FROM users WHERE (email='$e' AND pass='" . get_password_hash($p) .  "')";		
	  	$r = mysqli_query($dbc, $q);
	
	  	if (mysqli_num_rows($r) == 1) 
		{ // A match was made.
		
	  		// Get the data:
	  		$row = mysqli_fetch_array($r, MYSQLI_NUM); 
				
	  		// Store the data in a session:
	  		$_SESSION['user_id'] = $row[0];
	  		$_SESSION['user_firstname'] = $row[1];		
	  	} 
		else 
		{ // No match was made.
	  		$login_errors['login'] = 'The email address and password do not match those on file.';
	  	}
	
	  } // End of $login_errors IF.
  }

  include('header.html');
  include('footer.html');
?>
