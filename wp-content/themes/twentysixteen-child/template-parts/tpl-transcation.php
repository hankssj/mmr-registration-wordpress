<?php
/*
Template Name: Transcation
*/
get_header();

global $wpdb;

session_start();
?>
<?php
if($_GET['tra_type'] != ''){
	?><a href="<?php echo site_url(); ?>/view-all-transcation/">Back</a><?php
}
else{
	?><a href="<?php echo site_url(); ?>/admin-enrollment-form/">Back</a><?php
}
?>

<?php
// Delete transcation
if($_POST['deleteform'] != '' && isset($_POST['deleteform'])){
	$wpdb->query(
		              "DELETE  FROM payments
		               WHERE id = ".$_POST['deleteid'].""
		);
	$_SESSION['trasucc'] = 'Transcation Deleted Successfully.';
}
// End delete transcation

// Add new transacation
if($_POST['Submit']){

	global $wpdb;

	$amount = $_REQUEST['payment_amount'];
	
		$sql = "INSERT INTO `payments` (payment_type, payment_amount, payment_status, createdtime, cheque_num, user_ID, notes) VALUES (
					'cheque',
					'".$amount."',
					'pending',
					'".date("Y-m-d H:i:s")."',
					'".$_REQUEST['cheque_num']."',
					'".$_GET['id']."',
					'".$_REQUEST['notes']."'
					)";
			$wpdb->query($sql);
			$_SESSION['trasucc'] = 'Transcation Inserted Successfully.';
}
// End new add transcation
	echo "<h3>".$_SESSION['trasucc']."</h3>";
unset($_SESSION['trasucc']);
	?>
	<input type="button" name="addnewpayment" value="Add New Payment" id="addnewpayment">

	<form method="post" style="display: none" id="cheque_form">

		<table border="0">
			<tr>
				<td>Cheque Number</td>
				<td><input type="text" id="cheque_num" name="cheque_num" value="<?php echo $cheque_num; ?>" required></td>
			</tr>
			<tr>
				<td>Pay Amount ($)</td>
				<td><input type="text" class="traamount" id="payment_amount" name="payment_amount" value="<?php echo $paidamount; ?>" required></td>
			</tr>
			<tr>
				<td>Notes</td>
				<td><textarea name="notes"><?php echo $notes;?></textarea> </td>
			</tr>
			<tr>
				<td colspan="2"><input id="sbmittranscation" type="submit" name="Submit" value="Submit"></td>
			</tr>
		</table>
	</form>

	<?php
	// Display user payment data
	$querystr = "
				SELECT * 
				FROM payments
				WHERE user_ID = ".$_GET['id']."
				order by id
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
			<td>Action</td>
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
				<td>
				<form method="post" id="deleteform">
				<input type="hidden" name="deleteid" class="deleteid" value="">
				<a href="javascript:;" onClick="deletetranscation(<?php echo $formdata->id; ?>);">Delete</a>
				<input type="hidden" name="deleteform" value="Delete">
				</form>

				<?php
				if($_GET['tra_type'] != ''){
					?><a href="/edit-transcation/?id=<?php echo $formdata->id; ?>&userID=<?php echo $_GET['id']; ?>&tra_type=alltranscation">Edit</a><?php
				}
				else{
					?><a href="/edit-transcation/?id=<?php echo $formdata->id; ?>&userID=<?php echo $_GET['id']; ?>">Edit</a><?php
				}
				?>
				<?php
				if($_GET['tra_type'] != ''){
					?><br><a href="/more-transcation/?id=<?php echo $formdata->id; ?>&userID=<?php echo $_GET['id']; ?>&tra_type=alltranscation">Show More</a><?php
				}
				else{
					?><br><a href="/more-transcation/?id=<?php echo $formdata->id; ?>&userID=<?php echo $_GET['id']; ?>">Show More</a><?php
				}
				?>

				</td>
			</tr>
			<?php
		}
	}
	else{
		?><tr><td colspan="6"><center> No Data Found </center></td></tr><?php
	}
?>
</tbody>
</table>
<?php 
// Get notification
$qrynoti = "
	SELECT * 
	FROM payment_noti
	WHERE user_id = ".$_GET['id']."
	";

$notificationdata = $wpdb->get_results($qrynoti, OBJECT);

if(empty($notificationdata)){
	$sql = "INSERT INTO payment_noti
	          	(`user_id`,`count`) 
	   			values (".$_GET['id'].", ".$counter.")";
		$wpdb->query($sql);
}
else{

	$sql = "UPDATE payment_noti set
				count = ".$counter."
				WHERE user_id = ".$_GET['id']."";

		$wpdb->query($sql);
}
// Get notification
get_footer(); ?>