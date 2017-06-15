<?php
/*
Template Name: Admin edit Transcation
*/
get_header();

// Update transcation
if($_POST['submit']){

	$amount = $_REQUEST['payment_amount'];
	
	global $wpdb;
		$sql = "UPDATE payments set
				payment_amount = '".$amount."',
				cheque_num = '".$_REQUEST['cheque_num']."',
				notes = '".$_REQUEST['notes']."'
				WHERE id = ".$_GET['id']."";

		$wpdb->query($sql);
		session_start();
		$_SESSION['trasucc'] = 'Transcation Updated Successfully.';
		if($_GET['tra_type'] != ''){
			$redURL = site_url()."/transcation/?id=".$_GET['userID']."&tra_type=alltranscation";
		}
		else{
			$redURL = site_url()."/transcation/?id=".$_GET['userID'];
		}

		
		?>
		<script>
		jQuery(document).ready(function(){
			window.location.href = '<?php  echo $redURL; ?>';
		});
		</script>
		<?php
}
global $wpdb;
	$querystr = "
				SELECT * 
				FROM payments
				WHERE id = ".$_GET['id']."
				";

	$check_user = $wpdb->get_results($querystr, OBJECT);

	// Form for data
	?>
	<form method="post">
	
	<?php
	if(!empty($check_user)){
		foreach ($check_user as $formdata) {

			$amount = $formdata->payment_amount;
			

			if($formdata->payment_type == 'online'){
				?>
				<table>
				<tr>
				<td><label>Amount($)</label></td>
				<td><input type="text" class="traamount" name="payment_amount" value="<?php echo round($amount); ?>"></td>
				</tr>
				</table>
				<?php
			}
			else{
				?>
				<table>
				<tr>
				<td><label>Amount($)</label></td>
				<td><input type="text" class="traamount" name="payment_amount" value="<?php echo round($amount); ?>"></td>
				</tr>
				<tr>
				<td><label>Cheque Number</label></td>
				<td><input type="text" name="cheque_num" value="<?php echo $formdata->cheque_num; ?>"></td>
				</tr>
				<tr>
				<td><label>Notes</label></td>
				<td><textarea name="notes"><?php echo $formdata->notes;?></textarea></td>
				</tr>
				</table>
				<?php
			}
			
		}
	}
	
?>
<input type="submit" name="submit" id="sbmittranscation" value="submit">
</form>
<?php get_footer(); ?>