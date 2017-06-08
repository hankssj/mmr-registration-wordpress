<?php
/*
Template Name: Payment Options
*/

get_header();
session_start();
	
	// Payment Form
	$current_user = wp_get_current_user();
	global $wpdb;

	// Get Paid amount
	if($current_user->roles[0] != 'administrator'){
		$querystr = "
					SELECT * 
					FROM gravity_amount
					WHERE user_id = ".$current_user->ID."
					";

		$get_data = $wpdb->get_results($querystr, OBJECT);
		$entry_id = array();
		$paymentamount = '';
		foreach ($get_data as $formdata) {
			$paymentamount = $formdata->amount;
		}
		// Get paid amount
		$qryamount = "
					SELECT * 
					FROM payments
					WHERE user_ID = ".$current_user->ID."
					";

		$get_amount = $wpdb->get_results($qryamount, OBJECT);
		$paidary = array();
		foreach ($get_amount as $amountdata) {
			$paidary[] = $amountdata->payment_amount;
		}
		$paidamount = array_sum($paidary);

		

		$paymentamount = round($paymentamount - $paidamount);

		// End get paid amount
		$percent = ($paymentamount*3)/100;

		// Calculate payment amount
 		$increaseamount = $paymentamount + 0.30 + $percent; 

		?>
		<br><br>
		<p>You will now be directed to the payment gateway. Your Charge amount will be $<?php echo $increaseamount; ?>, which consists of your full balance of and a non-refundable credit-card fee of $<?php echo $increaseamount - $paymentamount; ?>. At the end of the transcation, you will be returned to the registration site.</p>

		<p>You can pay using a Paypal account, or with your credit card without creating Paypal account. To pay with credit card, look for the wording “Pay with a debit or credit card, or PayPal Credit” at the botton of the next screen.</p>
		 
		<p>The session with PayPal will open in a new window, so you will need to disable popup blocking temporarily.</p>
		<p>For online payments, ($0.30 + 3%) charge will be applied on total transaction amount
 </p>
		
		<div class="fullpaymentoptions">

		<form class="paypal" action="<?php echo site_url(); ?>/payment/" method="post" id="paypal_form">

			<input name="fullpaymentoptions" class="fullpay removerequire" value="<?php echo $paymentamount; ?>" type="radio" checked="checked"> <span>Pay the full balance</span>

			<?php if($paymentamount >= 150){ ?>
			<input name="fullpaymentoptions" class="depositepay removerequire" value="150" type="radio"> <span>Pay a deposit of $150 </span>
			<?php } ?>

			<input name="fullpaymentoptions" id="custompay" value="custom" type="radio"> <span>Pay any other amount (<= $<?php echo $increaseamount; ?> ) </span>
			<input style="display: none" class="custompay" type="text" placeholder="Enter Amount you want to pay online" name="fullpaymentoptionscustom">
			
			<br><br>
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="lc" value="UK" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
			<input type="hidden" name="first_name" value="Customer's First Name"  />
			<input type="hidden" name="last_name" value="Customer's Last Name"  />
			<input type="hidden" name="payer_email" value="customer@example.com"  />
			<input type="hidden" name="item_number" value="123456" / >
			<input type="submit" name="submit" id="paynow" value="Pay Now"/>
		</form>
	</div>
	<?php
	}
get_footer();
?>