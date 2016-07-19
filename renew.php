<?php
	require ('config.inc.php');
	redirect_invalid_user();

	include ('header.html');	
	require('forms.inc.php');
	
	require('mysql.inc.php');
	$q = "SELECT date_expires FROM users WHERE id={$_SESSION['user_id']}";	
	$r = mysqli_query($dbc, $q);
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$date_expires=$row['date_expires'];
?>
<h3>Renew Account</h3>
<p>Your Membership valid date(YYYY-MM-DD): <?php echo $date_expires;?></p>
<p>You will need to pay for a price of $10 HKD to obtain an annual subscription</p>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="H8KXT4EWPT7GE">
    <input type="hidden" name="custom" value="<?php echo $_SESSION['user_id']; ?>">
    <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<?php
	include('footer.html');
?>