<?php
/*
Template Name: Admin Enrollment Form
*/
get_header();

global $wpdb;

$current_user = wp_get_current_user();
if($current_user->roles[0] != 'administrator'){
?>
<script>
jQuery(document).ready(function(){
window.location.href = '<?php  echo site_url(); ?>';
});

</script>
<?php

}


$pagetitle = get_post_meta( get_the_ID(), 'page_title', true );
// Check if the custom field has a value.
if ( ! empty( $pagetitle ) ) {
    echo "<h2><center>".$pagetitle."<center></h2>";
}

if($current_user->roles[0] == 'administrator'){
session_start();
echo "<h2>";
echo "<div style='color: green;'>";
echo $_SESSION['succmsg'];
echo "</div>";
echo "</h2>";
unset($_SESSION['succmsg']);
if($_POST['deleteform'] != '' && isset($_POST['deleteform'])){
	global $wpdb;
	// Get all user id from form
	$qryGetID = "
				SELECT *
				FROM ".$wpdb->prefix."rg_lead
				WHERE created_by = ".$_POST['deleteid']."";

	$getUSer = $wpdb->get_results($qryGetID, OBJECT);
	foreach ($getUSer as $userdata) {
		
		$wpdb->query(
		              "DELETE  FROM ".$wpdb->prefix."rg_lead
		               WHERE id = ".$userdata->id.""
		);
		$wpdb->query(
		              "DELETE  FROM ".$wpdb->prefix."rg_lead_detail
		               WHERE lead_id = ".$userdata->id.""
		);
		// $wpdb->query(
		//               "DELETE  FROM payments
		//                WHERE user_ID = ".$userdata->created_by.""
		// );
	}
}

// Get all form data and user data
$user = wp_get_current_user();

$querystr = "
			SELECT DISTINCT *
			FROM wp_users	
			";

$check_user = $wpdb->get_results($querystr, OBJECT);
?>
	
	<input type="hidden" name="sortemail" value="<?php echo $sortemail; ?>">
	<input type="hidden" name="sortname" value="<?php echo $sortname; ?>">
	<table class="table-view" id="example">
		<thead>
			<tr>
			<th>Email</th>
			<th>Name</th>
			<th>Balance</th>
			<th></th>
			</tr>
		</thead>
	<tbody>
   	<?php
	foreach ($check_user as $getuserID) {

		$qryblncamount = "
					SELECT DISTINCT *
					FROM payments
					WHERE user_ID = ".$getuserID->ID."
					GROUP BY user_ID
					";

		$get_amount = $wpdb->get_results($qryblncamount, OBJECT);
		$paidaryamount = array();
		
		foreach ($get_amount as $amountdata) {
			
			$user_last = get_user_meta( $getuserID->ID );
			$user_info = get_userdata($getuserID->ID);

			if(!empty($amountdata)){
				?>
			<tr>
      		<td class=""><?php print_r($user_info->user_email); ?></td>
			<td class=""><?php echo $user_last['first_name'][0]." "; ?><?php echo $user_last['last_name'][0]; ?></td>
			<td class="">

				<?php
				$querygetblnc = "
					SELECT * 
					FROM gravity_amount
					WHERE user_id = ".$getuserID->ID."
					";
				$blnc = $wpdb->get_results($querygetblnc, OBJECT);
				$paymentblnc = '';
				foreach ($blnc as $formpayment) {
					$paymentblnc = $formpayment->amount;
				}
				// Get paid amount
				$qryblncamount = "
							SELECT * 
							FROM payments
							WHERE user_ID = ".$getuserID->ID."
							";
				$get_amount = $wpdb->get_results($qryblncamount, OBJECT);
				$paidaryamount = array();
				$transcationcounter = 0;
				foreach ($get_amount as $amountdata) {
					$transcationcounter++;
					$paidaryamount[] = $amountdata->payment_amount;
				}
				$paidamount = array_sum($paidaryamount);
		 		$paymentblnc = round($paymentblnc - $paidamount);
		 		
				echo "$";
				echo $paymentblnc;
				$qrynoti = "
						SELECT *
						FROM payment_noti
						WHERE user_id = ".$getuserID->ID."
						";

				$notificationdata = $wpdb->get_results($qrynoti, OBJECT);
				$counternotification = '';
				foreach ($notificationdata as $countnotify) {
					$counternotification =  $countnotify->count;
				}

				$unreadtranscation = $transcationcounter - $counternotification;
				?>

			</td>
			<td class="" >
			<form method="post" id="deleteform">
			<input type="hidden" name="deleteid" class="deleteid" value="">
			<a href="/transcation/?id=<?php echo $getuserID->ID; ?>&tra_type=alltranscation">Transcation</a>
			<?php
				if($unreadtranscation > 0){
					echo "Unread(".$unreadtranscation.")";
				}
				?>
			<input type="hidden" name="deleteform" value="Delete">
			</form>
			<!--
			<?php echo do_shortcode('[gv_edit_entry_link action="delete" entry_id="'.$entry_id.'" view_id="220" /]'); ?>
			-->
			</td>
      	</tr>
      	<?php
			}
			
		}

	}
	?>
	</tbody>
   	<tfoot>
		<tr>
			<th>Email</th>
			<th>Name</th>
			<th>Balance</th>
			<th></th>
			</tr>
   </tfoot>
</table>
<?php
}


get_footer(); ?>