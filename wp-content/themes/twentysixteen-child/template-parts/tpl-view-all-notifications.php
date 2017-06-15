<?php
/*
Template Name: View All Notifications
*/
get_header();
global $wpdb;
?>
<a href="<?php echo site_url(); ?>/dashboard/">Back</a>
<?php
	// Display user payment data
	$querystr = "
				SELECT * 
				FROM payments order by id DESC
				";
	$check_user = $wpdb->get_results($querystr, OBJECT);
	?>
	
	<table class="table-view" id="example">
		<thead>
			<tr>
			<td>Transcation ID</td>
			<td>Amount</td>
			<td>Payment Status</td>
			<td style="display: none">id</td>
			<td>Payer Email</td>
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
				<td><?php echo $formdata->txnid; 
				if($formdata->txnid == ''){
					echo '-';
				}
				?></td>
				<td><?php 
				$amount = $formdata->payment_amount;
 		 		echo "$".round($amount); ?></td>
				<td><?php echo ucfirst($formdata->payment_status); ?></td>
				<td style="display: none;"><?php echo $formdata->id; ?></td>
				<td><?php echo $formdata->payer_email; 
				if($formdata->payer_email == ''){
					echo '-';
				}
				?></td>
				<td>
 		 		<a href="/more-transcation/?id=<?php echo $formdata->id; ?>&tra_type=viewtranscation">Show More</a>
				</td>
			</tr>
			<?php
		}
	}
	else{
		?><tr><td colspan="5"><center> No Data Found </center></td></tr><?php
	}
?>
</tbody>
   	<tfoot>
		<tr>
			<td>First Name</td>
			<td>Last Name</td>
			<td>Payment Type</td>
			<td>Amount</td>
			<td>Action</td>
			</tr>
   </tfoot>
</table>
<?php
get_footer(); ?>