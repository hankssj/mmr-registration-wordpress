<?php
/*
Template Name: User Transcation
*/
get_header();
global $wpdb;
session_start();
$current_user = wp_get_current_user()
?>

	<form method="post" style="display: none" id="cheque_form">

		<table border="0">
			<tr>
				<td>Cheque Number</td>
				<td><input type="text" id="cheque_num" name="cheque_num" value="<?php echo $cheque_num; ?>" required></td>
			</tr>
			<tr>
				<td>Pay Amount ($)</td>
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
	// Display user payment data
	$querystr = "
				SELECT * 
				FROM payments
				WHERE user_ID = ".$current_user->ID."
				";
	$check_user = $wpdb->get_results($querystr, OBJECT);
	?>
	<table>
	<thead>
		<tr>
			<td>Payment Type</td>
			<td>Amount</td>
			<td>Cheque Number</td>
			<td>Payment Date</td>
			<td>Notes</td>
		</tr>
	</thead>
	<tbody>
	<?php
	if(!empty($check_user)){
		$counter = 0;
		foreach ($check_user as $formdata) {
			$counter++;
			?>
			<tr>
				<td><?php echo ucfirst($formdata->payment_type); ?></td>
				<td><?php 
				$amount = $formdata->payment_amount;
				
 		 echo "$".round($amount); ?></td>
				<td><?php
				if($formdata->payment_type == 'online'){
					echo "Online/CC";
				}
				else{
				 	echo $formdata->cheque_num; 
				}
				?></td>
				<td><?php echo date("d-m-Y", strtotime($formdata->createdtime)); ?></td>
				<td><?php 
				if($formdata->payment_type == 'online'){
					echo "Online Payment";
				}
				else{
					echo $formdata->notes;
				} ?></td>
			</tr>
			<?php
		}
	}
	else{
		?><tr><td colspan="5"><center> No Data Found </center></td></tr><?php
	}
?>
</tbody>
</table>
<?php get_footer(); ?>