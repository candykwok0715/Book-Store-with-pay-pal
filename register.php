<?php
  require('config.inc.php');
  require('mysql.inc.php');

  include('forms.inc.php');
  
  $reg_errors = array();
  
  // Check for a form submission:
  if ($_SERVER['REQUEST_METHOD'] == 'POST') 
  {	
  	// Check for an email address:
  	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
    {
		$e = mysqli_real_escape_string($dbc, $_POST['email']);
	}
    else      
	{
  		$reg_errors['email'] = 'Please enter a valid email address.';
  	}

  	// Check for a password and match against the confirmed password:
  	if ($_POST['pass1'] == $_POST['pass2']) 
	{
		$p = mysqli_real_escape_string($dbc, $_POST['pass1']);
	}
	else
	{
  		$reg_errors['pass2'] = 'The two passwords you entered did not match.';
  	}

	$u = mysqli_real_escape_string($dbc, $_POST['username']);
	$fn = mysqli_real_escape_string ($dbc, $_POST['first_name']);
	$ln = mysqli_real_escape_string ($dbc, $_POST['last_name']);
	
  	if (empty($reg_errors)) 
	{ 
		// At this point, the email address of the new user is in $e,
		// the password is in $p, the username is in $u,
		// the first name is in $fn, and the last name is in $ln
		
		// Make sure the email address and username are available:
		$q = "SELECT email, username FROM users WHERE email='$e' OR username='$u'";
		$r = mysqli_query($dbc, $q);
		
		// Get the number of rows returned:
		$rows = mysqli_num_rows($r);
		
		if ($rows == 0) 
		{				
			// Add the user to the database, with the expiration date set to today.
			$q = "INSERT INTO users (username, email, pass, first_name, last_name, date_expires) VALUES ('$u', '$e', '"  .  get_password_hash($p) .  "', '$fn', '$ln', NOW())";
						
			$r = mysqli_query($dbc, $q);
            $id = mysqli_insert_id($dbc);
            header("Location: subscribe.php?user_id={$id}");
	  		exit();							
			
		} 
		else 
		{ // The email address or username is not available.
			if ($rows == 2) 
			{ // Both are taken.				
				$reg_errors['email'] = 'This email address has already been registered.';			
				$reg_errors['username'] = 'This username has already been registered.';			
			} 
			else 
			{ // One or both may be taken.	
			    // Get the row:
				$row = mysqli_fetch_array($r, MYSQLI_NUM);
									
				if ($row[0] == $_POST['email']) 
				{   // Email match.
					$reg_errors['email'] = 'This email address has already been registered.';
				} 
				
				if ($row[1] == $_POST['username']) 
				{   // Username match.
					$reg_errors['username'] = 'This username has already been registered.';			
				}
			} // End of $rows == 2 ELSE.	
		} // End of $rows == 0 IF.
	}
  } // End of form submission checking.	
?>
<?php include ('header.html'); ?>
<h3>Register</h3>

<p>
Access to the site's content is available to registered users at a cost
of $10.00 HKD per year. Use the form below to begin the registration process.
</p>

<p>
<b>Note: All fields are required.</b> After completing this form, you will be presented with the opportunity to securely pay for your yearly subscription.
</p>

<form action="register.php" method="post">

<p>First Name<br>
<?php 
  create_form_input('first_name', 'text', $reg_errors); 
?>
</p>

<p>Last Name<br>
<?php 
  create_form_input('last_name', 'text', $reg_errors); 
?>
</p>

<p>Username<br>
<?php 
  create_form_input('username', 'text', $reg_errors); 
?>
</p>

<p>Email Address<br>
<?php 
  create_form_input('email', 'text', $reg_errors); 
?>
</p> 

<p>Password<br>
<?php 
  create_form_input('pass1', 'password', $reg_errors);
?>
</p> 

<p>Confirm Password<br>
<?php 
  create_form_input('pass2', 'password', $reg_errors); 
?>
</p>

<input type="submit" name="submit_button" value="Register" id="submit_button"> 
</form>

<?php
  include('footer.html');
?>
