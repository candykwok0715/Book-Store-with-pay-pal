<?php
    require ('config.inc.php');
//    redirect_invalid_user();
    include ('header.html');
?>
        The payment through PayPal was not completed. <br>
        You have a valid membership at this site, but you will not be able to view any contect until you complete the PayPal transaction successfully. <br>
<?php if (!isset($_SESSION['user_id']))
			echo 'You can do so by clicking on the Renew Account link after loggimg in.<br>';
?>

<?php
    include('footer.html');
?>