<?php
/*
Template Name: Event Form
*/
get_header();


if ( is_user_logged_in() ) {

$user = wp_get_current_user();

global $wpdb;

$querystr = "
    SELECT * 
    FROM wp_rg_lead
    WHERE created_by = $user->ID
	AND form_id = 1
 ";

$check_user = $wpdb->get_results($querystr, OBJECT);

$entry_id = '';
			foreach ($check_user as $formdata) {
			$FormID = $formdata->form_id;
			$entry_id = $formdata->id;
			}

if($FormID == ''){
echo do_shortcode('[logindata]');
echo do_shortcode('[gravityform id="1" title="false" description="false"]');
}
else{
	echo do_shortcode('[gv_entry_link action="edit" entry_id="'.$entry_id.'" view_id="220" /]'); 

	?> <?php
	// Get Form Data
	$queryFormData = "
	    SELECT * 
	    FROM wp_rg_lead_detail
	    WHERE lead_id = $formdata->form_id
	 ";

	$get_data = $wpdb->get_results($queryFormData, OBJECT);

	foreach ($get_data as $alldata) {
		// End Get Form data	
	}
}
}
else{
	echo "<a href='".site_url()."/login'>Login First</a>";
}


get_footer(); ?>