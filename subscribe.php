<?php
    include ('header.html');
?>
<h3>Thank you for registering!</h3>
<p>To complete the process, please now click the button below so that you may pay for your
website access via PayPal.</p>
<p>The cost is $10 HKD per year.</p>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="JHQYNY9YQY9AE">
    <input type="hidden" name="custom" value="<?php echo $_GET['user_id']; ?>">
    <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

<?php include('footer.html'); ?>