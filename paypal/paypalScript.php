<?php

define( 'SHORTINIT', true );
require_once( '../wp-load.php' );
require_once dirname( __FILE__ ) . '../../wp-content/plugins/eventBookingPro/helpers.php';
require_once dirname( __FILE__ ) . '../../wp-content/plugins/eventBookingPro/EmailService.php';
include('ipnlistener.php');

ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');
error_log("=== ipn attempt ===");

global $wpdb;

$settings = $wpdb->get_row( "SELECT force_ssl_v3, sandbox  FROM " . EventBookingHelpers::getTableName('settings')." where id=1 ");

$listener = new IpnListener();

$listener->force_ssl_v3 = ($settings->force_ssl_v3 == "true");

//$listener->use_ssl = false;

$listener->use_sandbox = ($settings->sandbox == "true");

try {
	$listener->requirePostMethod();
	$verified = $listener->processIpn();
} catch (Exception $e) {
	error_log("Error: " . $e->getMessage());
	exit(0);
}

if ($verified) {
	$payment_status = (string)$_POST['payment_status'];
	$txn_id = (string)$_POST['txn_id'];
	$paymentId = $_POST['custom'];

	error_log("Transaction verified: " . $paymentId . ', paypal id: '.$txn_id);

	$wpdb->update(EventBookingHelpers::getTableName('payments'),
		 array('status'=>$payment_status,'txn_id'=>$txn_id), array('id' => $paymentId));

	if(paymentDone($payment_status)) {
		$emailSendStatus = EmailService::createEmailAndSend($paymentId);

		handleEmailStatus($emailSendStatus);

	} else if ($payment_status == 'Refunded') {
		$emailSendStatus = EmailService::createEmailAndSend($paymentId, "refund");

		handleEmailStatus($emailSendStatus);
	}

} else {
	error_log("Transaction failed: ".$listener->getTextReport());
}
error_log("=== ipn attempt end ===");

function paymentDone ($status) {
	switch ($status) {
		case 'Completed':
		case 'Processed':
		case 'Created':
			return true;
			break;
		case 'Canceled_Reversal':
		case 'Denied':
		case 'Expired':
		case 'Failed':
		case 'Pending': //pending_reason
		case 'Refunded':
		case 'Reversed'://ReasonCode
		case 'Voided':
		default:
			return true;
	}
}

function handleEmailStatus($emailSendStatus) {
	if ($emailSendStatus[0] != EmailService::SENT_SUCCESS) {
    error_log("error sending email to customer: " . print_R($emailSendStatus[0]));
  }
  if ($emailSendStatus[1] != EmailService::SENT_SUCCESS) {
    error_log("error sending email to admin: " . print_R($emailSendStatus[1]));
  }
}

?>
