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
			FROM ".$wpdb->prefix."rg_lead		
			WHERE form_id = 1 
			AND created_by != ".$user->ID."
			GROUP BY created_by DESC
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
			<th>Instrument</th>
			<th>Dorms?</th>
			<th>Meals?</th>
			<th>First?</th>
			<th>Vol?</th>
			<th>Balance</th>
			<th>Eval</th>
			<th></th>
			</tr>
		</thead>
	<tbody>
   	<?php
	foreach ($check_user as $getuserID) {

		$querydetails = "
			SELECT * 
			FROM ".$wpdb->prefix."rg_lead_detail
			WHERE lead_id = ".$getuserID->id."
			";

			$get_user_details = $wpdb->get_results($querydetails, OBJECT);
			$instrumental = '';
			$meal = 'N';
			$room = '';
			$single = 'N';
			$dorm = '';
			$first = 'N';
			$vol = '';
			
		foreach ($get_user_details as $getUserDetails) {
			
			$meta_value = gform_get_meta( $entry_id, $meta_key );

			if($getUserDetails->field_number == '23'){
				  $instrumental = $getUserDetails->value;
			}
			if($getUserDetails->field_number == '56'){
				 	$meal = $getUserDetails->value;
				 	
			}
			if($getUserDetails->field_number == '21'){
				$room = $getUserDetails->value;
			}
			if($getUserDetails->field_number == '51.1'){
				$single = $getUserDetails->value;
				if(trim($single) == ''){
				  	$single = 'N';
				  }
			}
			if($getUserDetails->field_number == '55'){
				$dorm = $getUserDetails->value;
			}
			if($getUserDetails->field_number == '17.1'){
				$first = $getUserDetails->value;
				if($first == ''){
				  	$first = 'N';
				  }
			}
			if($getUserDetails->field_number == '45'){
				$vol = $getUserDetails->value;
			}
		}
		
		$entry_id = $getuserID->id;
		$user_id = $getuserID->created_by;
		$user_last = get_user_meta( $user_id ); 
		$user_info = get_userdata($user_id);
	?>
      	<tr>
      		<td class=""><?php print_r($user_info->user_email); ?></td>
			<td class=""><?php echo $user_last['first_name'][0]." "; ?><?php echo $user_last['last_name'][0]; ?></td>
			<td class=""><?php echo $instrumental; ?></td>
			<td class=""><?php echo $dorm; ?></td>
			<td class=""><?php echo $meal; ?></td>
			<td class=""><?php echo $first; ?></td>
			<td class=""><?php echo $vol; ?></td>
			<td class="">
				<?php
				$querygetblnc = "
					SELECT * 
					FROM gravity_amount
					WHERE user_id = ".$user_id."
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
					WHERE user_ID = ".$user_id."
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
						WHERE user_id = ".$user_id."
						";

				$notificationdata = $wpdb->get_results($qrynoti, OBJECT);
				$counternotification = '';
				foreach ($notificationdata as $countnotify) {
					$counternotification =  $countnotify->count;
				}

				$unreadtranscation = $transcationcounter - $counternotification;
		?>
			</td>
			<td class="">N</td>
			<td class="" >
			<?php echo do_shortcode('[gv_edit_entry_link action="edit" entry_id="'.$entry_id.'" view_id="220"]Edit[/gv_edit_entry_link]'); ?>
			<form method="post" id="deleteform">
			<input type="hidden" name="deleteid" class="deleteid" value="">
			<a id="deletebutton" href="javascript:;" onClick="deletefun(<?php echo $user_id; ?>);">Delete</a>
			<a href="/transcation/?id=<?php echo $user_id; ?>">Transcation</a>
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
	?>
	</tbody>
   	<tfoot>
		<tr>
			<th>Email</th>
			<th>Name</th>
			<th>Instrument</th>
			<th>Dorms?</th>
			<th>Meals?</th>
			<th>First?</th>
			<th>Vol?</th>
			<th>Balance</th>
			<th>Eval</th>
			<th></th>
			</tr>
   </tfoot>
</table>
<?php
}
get_footer(); ?>