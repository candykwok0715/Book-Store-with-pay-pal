<?php
require('mysql.inc.php');

// STEP 1: read POST data

// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
    $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}


// Step 2: POST IPN data back to PayPal to validate

$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

if( !($res = curl_exec($ch)) ) {
    curl_close($ch);
    throw new ErrorException('cannot use curl');
}
curl_close($ch);


// STEP 3: Inspect IPN validation result and act accordingly
if (isset($_POST['mc_amount3'])) {
    $expectedItemName = array(
        'Renew account',
        'Subscribe'
    );

    $expected = array(
        'mc_amount3' => 10,
        'mc_currency' => 'HKD',
        'receiver_email' => 'candybookstoretesting@gmail.com'
    );

    $isValid = true;
    $error = array();
    foreach($expected as $key => $value){
        if(isset($_POST[$key]) && $_POST[$key] == $value){
            continue;
        }

        $error[$key] = $_POST[$key];
        $isValid = false;
    }

    if(!in_array($_POST['item_name'], $expectedItemName)){
        $error['item_name'] = $_POST['item_name'];
        $isValid = false;
    }

    if($isValid && isset($_POST['custom'])){
        $user_id = $_POST['custom'];
        $q = "SELECT date_expires FROM users WHERE id={$user_id}";
        $r = mysqli_query($dbc, $q);
        $row = mysqli_fetch_array($r,MYSQLI_ASSOC);
        $date_expires=$row['date_expires'];
        if ($date_expires<=date("Y-m-d")){   //check has been expired or not
            $q="UPDATE users SET date_expires = DATE_ADD(NOW(), INTERVAL 1 YEAR)  where id={$user_id}";
        }
        else
        {
            $q="UPDATE users SET date_expires = DATE_ADD('$date_expires 00:00:00', INTERVAL 1 YEAR)  where id={$user_id}";
        }
        $r = mysqli_query($dbc, $q);
        $q = "SELECT username,date_expires FROM users WHERE id={$user_id}";
        $r = mysqli_query($dbc, $q);
        $row = mysqli_fetch_array($r,MYSQLI_ASSOC);

        //send email to admin
        $email_title="Renew Membership - ".$row['username'];
        $body = $row['username']." renew membership. Now the Membership valid date(YYYY-MM-DD) is ".$row['date_expires'].'.';
        $q = "SELECT email FROM users WHERE type='admin'";
        $r = mysqli_query($dbc, $q);
        while($row=mysqli_fetch_array($r,MYSQLI_ASSOC)){
            mail ($row['email'], $email_title, $body, 'From: no-reply@comp.polyu.edu.hk');
        }
    }
}
?>