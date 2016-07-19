<?php
    require ('config.inc.php');
//    redirect_invalid_user();
    include ('header.html');
  if (isset($_SESSION['user_id'])) 
		echo 'Thank you. Your renewal will be process after the payment is confirmed.';
	else 
		echo 'Thank you. Your subscription will be updated after the payment is confirmed. Please login to enjoy Reading.';
?>
<?php
    include('footer.html');
?>