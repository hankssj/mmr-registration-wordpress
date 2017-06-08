<?php
/*
Template Name: Cheque Payment
*/
get_header();

?>

<?php
$current_user = wp_get_current_user();
if($_POST['Submit']){

	global $wpdb;

	$qryamount = "
					SELECT * 
					FROM payments
					WHERE user_ID = ".$current_user->ID."
					AND payment_type = 'cheque'
					";
	$get_amount = $wpdb->get_results($qryamount, OBJECT);

	if (empty($get_amount)) {

		$sql = "INSERT INTO `payments` (payment_type, payment_amount, payment_status, createdtime, cheque_num, user_ID, notes) VALUES (
					'cheque',
					'".$_REQUEST['payment_amount']."',
					'pending',
					'".date("Y-m-d H:i:s")."',
					'".$_REQUEST['cheque_num']."',
					'".$current_user->ID."',
					'".$notes."'
					)";
			$wpdb->query($sql);
	}
	else{
		 $sql = "UPDATE payments set
				payment_amount = ".$_REQUEST['payment_amount'].",
				cheque_num = ".$_REQUEST['cheque_num'].",
				notes = '".$_REQUEST['notes']."'
				WHERE user_ID = ".$current_user->ID."
				AND payment_type = 'cheque'";

		$wpdb->query($sql);

	}

}


		$qryamount = "
					SELECT * 
					FROM payments
					WHERE user_ID = ".$current_user->ID."
					AND payment_type = 'cheque'
					";

		$get_amount = $wpdb->get_results($qryamount, OBJECT);
		$paidamount = '';
		$cheque_num = '';
		foreach ($get_amount as $amountdata) {
			$paidamount = $amountdata->payment_amount;
			$cheque_num = $amountdata->cheque_num;
			$notes = $amountdata->notes;
		}


?>
<form method="post">

<table border="0">

	<tr>
		<td>Cheque Number</td>
		<td><input type="text" id="cheque_num" name="cheque_num" value="<?php echo $cheque_num; ?>" required></td>
	</tr>

	<tr>
		<td>Pay Amount</td>
		<td><input type="text" id="payment_amount" name="payment_amount" value="<?php echo $paidamount; ?>" required></td>
	</tr>

	<tr>
		<td>Notes</td>
		<td><textarea name="notes"><?php echo $notes;?></textarea> </td>
	</tr>

	<tr>
		<td colspan="2"><input type="submit" name="Submit" value="Submit"></td>
	</tr>

</table>

</form>



<?php

get_footer();
?>