<?php
/*
Template Name: Payment
*/
session_start();

// PayPal Integration starts here

// Redirect URL
$payemail = get_post_meta( get_the_ID(), 'payer_email', true );

$paypal_email = $payemail;
$return_url = site_url()."/dashboard/";
$cancel_url = site_url();
$notify_url = site_url()."/dashboard/";

$item_name = 'MMR Enrollment';

if($_REQUEST['fullpaymentoptions'] == 'custom'){
	$item_amount = $_REQUEST['fullpaymentoptionscustom'];
}
else{
	$item_amount = $_REQUEST['fullpaymentoptions'];
}

$percent = ($item_amount*3)/100;

$item_amount = $item_amount + 0.30 + $percent; 

// check_txnid - paypal default function to check transaction id
function check_txnid($tnxid){
	global $link;
	return true;
	$valid_txnid = true;

	//get result set

	$sql = "SELECT * FROM `payments` WHERE txnid = ".$tnxid."";

	$check_user = $wpdb->get_results($sql, OBJECT);

	if (!empty($check_user)) {
		$valid_txnid = false;
	}

	return $valid_txnid;
}


function check_price($price, $id){
	$valid_price = false;
	return true;
}
// Updates payment info to database
function updatePayments($data){
	global $link;
	global $wpdb;
	if (is_array($data)) {

		global $wpdb;
		$current_user = wp_get_current_user();

		$sql = "INSERT INTO `payments` (payment_type, txnid, payment_amount, payment_status, itemid, createdtime, user_ID) VALUES (
				'online',
				'".$data['txn_id']."',
				'".$data['payment_amount']."',
				'".$data['payment_status']."',
				'".$data['item_number']."',
				'".date("Y-m-d H:i:s")."',
				'".$current_user->ID."'
				)";
		$wpdb->query($sql);
	}
}


// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	$querystring = '';
	
	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	$current_user = wp_get_current_user();
	
	// Append querystring with custom field
	//$querystring .= "&user_ID=".$current_user->ID;

	$payurl = get_post_meta( get_the_ID(), 'url', true );
	$url = "".$payurl."/cgi-bin/webscr".$querystring;
	wp_redirect( $url );
	
	// Redirect to paypal IPN
	//header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
	exit();
} else {
	//Database Connection
	
	// Response from Paypal

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
	
	$current_user = wp_get_current_user();

	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['user_ID']				= $current_user->ID;
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];
	
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	$ssl_url = get_post_meta( get_the_ID(), 'ssl_url', true );
	
	$fp = fsockopen ($ssl_url, 443, $errno, $errstr, 30);
	
	if (!$fp) {
		// HTTP ERROR
		
	} else {
		fputs($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp($res, "VERIFIED") == 0) {
				
				
				// Validate payment (Check unique txnid & correct price)
				$valid_txnid = check_txnid($data['txn_id']);
				$valid_price = check_price($data['payment_amount'], $data['item_number']);
				// PAYMENT VALIDATED & VERIFIED!
				if ($valid_txnid && $valid_price) {
					
					$orderid = updatePayments($data);
					
					if ($orderid) {
						
					} else {
						
					}
				} else {
					
				}
			
			} else if (strcmp ($res, "INVALID") == 0) {
			
			}
		}
	fclose ($fp);
	}
}

get_header();
get_footer();
?>