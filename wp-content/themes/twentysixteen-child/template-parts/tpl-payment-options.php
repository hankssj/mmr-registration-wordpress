<?php
/*
Template Name: Payment Options
*/

get_header();
session_start();
	
	// Payment Form
	$current_user = wp_get_current_user();

	$user_last = get_user_meta( $current_user->ID ); 
	$user_info = get_userdata( $current_user->ID );
	
	
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

 		// End get paid amount
		$percentdeposite = (150*3)/100;

 		$depositeamount = 150 + 0.30 + $percentdeposite;
		?>
		<br><br>

		<p>You will now be directed to the payment gateway. Your Charge amount will be <b>$</b><b class="fullamount"><?php echo $increaseamount; ?></b><b class="depositeamount" style="display: none"><?php echo $depositeamount; ?></b><b class="customamount" style="display: none">0</b>, which consists of your full balance of and a non-refundable credit-card fee of 
		<b>$</b><b class="fullamount"><?php echo $increaseamount - $paymentamount; ?></b><b class="depositeamount" style="display: none"><?php echo $depositeamount - 150; ?></b><b class="customamount cuspercent" style="display: none">0</b>. At the end of the transcation, you will be returned to the registration site.</p>

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
			<input type="hidden" id="paybleamount" value="<?php echo $paymentamount; ?>" name="">
			<input name="fullpaymentoptions" id="custompay" value="custom" type="radio"> <span>Pay any other amount 
			<!--
			(<= $<?php //echo $increaseamount; ?> ) 
			-->
			</span>
			<input style="display: none" class="custompay" type="text" placeholder="Enter Amount you want to pay online" name="fullpaymentoptionscustom">
			
			<br><br>
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="cmd" value="_xclick" />
			<input type="hidden" name="no_note" value="1" />
			<input type="hidden" name="lc" value="UK" />
			<input type="hidden" name="currency_code" value="USD" />
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
			<input type="hidden" name="first_name" value="<?php print_r($user_last->first_name); ?>"  />
			<input type="hidden" name="last_name" value="<?php print_r($user_last->last_name); ?>"  />
			<input type="hidden" name="payer_email" value="<?php print_r($user_info->user_email); ?>"  />
			<input type="hidden" name="item_number" value="123456" / >
			<input type="submit" name="submit" id="paynow" value="Pay Now"/>
		</form>
	</div>
	<?php
	}
get_footer();
?>