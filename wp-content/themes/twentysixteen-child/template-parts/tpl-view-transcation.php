<?php
/*
Template Name: Admin View Transcation
*/
get_header();

if($_GET['tra_type'] == 'alltranscation'){
$redURL = site_url()."/transcation/?id=".$_GET['userID']."&tra_type=alltranscation";
}
if($_GET['tra_type'] == 'viewtranscation'){
$redURL = site_url()."/view-all-notifications/";
}
else{
$redURL = site_url()."/transcation/?id=".$_GET['userID'];
}
?>
<a href="<?php  echo $redURL; ?>">Back</a>

<?php
global $wpdb;
	$querystr = "
				SELECT * 
				FROM payments
				WHERE id = ".$_GET['id']."
				";

	$check_user = $wpdb->get_results($querystr, OBJECT);

	// Form for data
	?>
	
	<?php
	if(!empty($check_user)){
		foreach ($check_user as $formdata) {

			if($formdata->payment_type == 'online'){
				?>
				<table>
				<tr>
				<td><label>Payment Mode</label></td>
				<td><label><?php echo ucfirst($formdata->payment_type); ?></label></td>
				</tr>
				<tr>
				<td><label>Transcation ID</label></td>
				<td><label><?php echo $formdata->txnid; ?></label></td>
				</tr>
				<tr>
				<td><label>Payment Status</label></td>
				<td><label><?php echo $formdata->payment_status; ?></label></td>
				</tr>
				<tr>
				<td><label>Payment Amount</label></td>
				<td><label><?php echo "$".round($formdata->payment_amount); ?></label></td>
				</tr>
				<tr>
				<td><label>Payer Email</label></td>
				<td><label><?php echo $formdata->payer_email; ?></label></td>
				</tr>
				<tr>
				<td><label>Payer ID</label></td>
				<td><label><?php echo $formdata->payer_id; ?></label></td>
				</tr>
				<tr>
				<td><label>Payer Status</label></td>
				<td><label><?php echo $formdata->payer_status; ?></label></td>
				</tr>
				<tr>
				<td><label>First Name</label></td>
				<td><label><?php echo $formdata->first_name; ?></label></td>
				</tr>
				<tr>
				<td><label>Last Name</label></td>
				<td><label><?php echo $formdata->last_name; ?></label></td>
				</tr>
				<tr>
				<td><label>Payment Fee</label></td>
				<td><label><?php echo "$".$formdata->payment_fee; ?></label></td>
				</tr>
				<tr>
				<td><label>Paymen Gross</label></td>
				<td><label><?php echo "$".$formdata->payment_gross; ?></label></td>
				</tr>
				<tr>
				<td><label>Payment Type</label></td>
				<td><label><?php echo ucfirst($formdata->type); ?></label></td>
				</tr>
				<tr>
				<td><label>Transcation Type</label></td>
				<td><label><?php echo $formdata->txn_type; ?></label></td>
				</tr>
				<tr>
				<td><label>Receiver ID</label></td>
				<td><label><?php echo $formdata->receiver_id; ?></label></td>
				</tr>
				<tr>
				<td><label>Notify Version</label></td>
				<td><label><?php echo $formdata->notify_version; ?></label></td>
				</tr>
				<tr>
				<td><label>Verify Sign</label></td>
				<td><label><?php echo $formdata->verify_sign; ?></label></td>
				</tr>
				<tr>
				<td><label>Payment Date</label></td>
				<td><label>
				<?php echo date("d-m-Y h:i a", strtotime($formdata->createdtime)); ?>
				</tr>
				</table>
				<?php
			}
			else{
				?>
				<table>
				<tr>
				<td><label>Payment Mode</label></td>
				<td><label><?php echo ucfirst($formdata->payment_type); ?></label></td>
				</tr>
				<tr>
				<td><label>Amount($)</label></td>
				<td><label><?php echo round($formdata->payment_amount); ?></label></td>
				</tr>
				<tr>
				<td><label>Cheque Number</label></td>
				<td><label><?php echo $formdata->cheque_num; ?></label></td>
				</tr>
				<tr>
				<td><label>Notes</label></td>
				<td><label><?php echo $formdata->notes;?></label></td>
				</tr>
				<tr>
				<td><label>Payment Date</label></td>
				<td><label><?php echo $formdata->createdtime; ?></label></td>
				</tr>
				</table>
				<?php
			}
		}
	}
?>
<?php get_footer(); ?>