<?php
/*
Template Name: Admin Enrollment Form
*/
get_header();

global $wpdb;


if($_GET['sort'] == ''){
	$sort = 'DESC';
}
else if($_GET['sort'] == 'ASC'){
	$sort = 'DESC';
}
else if($_GET['sort'] == 'DESC'){
	$sort = 'ASC';
}

$querystr = "
			SELECT DISTINCT *
			FROM wp_rg_lead
			WHERE form_id = 1 GROUP BY created_by ".$sort."
			";

$check_user = $wpdb->get_results($querystr, OBJECT);
?>
	
	<input type="hidden" name="sort" value="<?php echo $sort; ?>">
	<table class="table-view">
		<thead>
			<tr>
			<th>Email</th>
			<th><a href="?sort=<?php echo $sort; ?>">Name</a></th>
			<th>Instrument</th>
			<th>Dorms?</th>
			<th>Single</th>	
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
			FROM wp_rg_lead_detail
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
			// echo "<pre>"; print_r($getUserDetails); echo "</prE>"; 
			// echo "<br>";

			$meta_value = gform_get_meta( $entry_id, $meta_key );

			if($getUserDetails->field_number == '23'){
				  $instrumental = $getUserDetails->value;
				  
			}
			if($getUserDetails->field_number == '31'){
				 	$meal = $getUserDetails->value;
				 	if($meal == ''){
				  	$meal = 'N';
				  }
				 	
			}
			if($getUserDetails->field_number == '21'){
				$room = $getUserDetails->value;

			}
			if($getUserDetails->field_number == '28'){
				$single = $getUserDetails->value;
				if(trim($single) == ''){
				  	$single = 'N';
				  }
			}
			if($getUserDetails->field_number == '44'){
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
			<td class=""><?php echo $single; ?></td>
			<td class=""><?php echo $meal; ?></td>
			<td class=""><?php echo $first; ?></td>
			<td class=""><?php echo $vol; ?></td>
			<td class="">$ 0</td>
			<td class="">N</td>
			<td class="">
			<?php echo do_shortcode('[gv_entry_link action="edit" entry_id="'.$entry_id.'" view_id="220" /]'); ?>
			<?php echo do_shortcode('[gv_entry_link action="delete" entry_id="'.$entry_id.'" view_id="220" /]'); ?>
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
			<th>Single</th>	
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
get_footer();
?>