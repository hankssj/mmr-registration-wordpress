<?php
/*
Template Name: Event Form
*/
get_header();



// For login user
if ( is_user_logged_in() ) {

$user = wp_get_current_user();

global $wpdb;

$pagetitle = get_post_meta( get_the_ID(), 'add_form_title', true );
// Check if the custom field has a value.
if ( ! empty( $pagetitle ) ) {
    echo "<h2>".$pagetitle."</h2><br>";
}


// Get data of form
$querystr = "
		    SELECT * 
		    FROM ".$wpdb->prefix."rg_lead
		    WHERE created_by = $user->ID
			AND form_id = 1
			";

			$check_user = $wpdb->get_results($querystr, OBJECT);


			$entry_id = array();
			foreach ($check_user as $formdata) {
			$FormID = $formdata->form_id;
			$entry_id[] = $formdata->id;
			}

if($FormID == ''){
	echo "<div class='custom-enrollment'>";
	echo "<h3>Your Enrollment Info</h3>";
	echo "<div class='custom-scroll'><h4>Total Amount : </h4><span class='custotal-amount'></span></div>";
	echo do_shortcode('[gravityform id="1" title="false" description="false"]');
	echo "</div>";
	
	
	echo "<div class='custom-contact'>";
	echo "<h3>Your Contact Info:</h3>";
	echo do_shortcode('[logindata]');
	echo "</div>";
	
}
else{
	$current_user = wp_get_current_user();
	// If role is administrator
	if($current_user->roles[0] == 'administrator'){
		echo "<a href='".site_url()."/admin-enrollment-form/'> View All Enrolled Form </a>";
	}
	else{
	// If role is normal user
	echo do_shortcode('[gv_edit_entry_link action="edit" entry_id="'.$entry_id[0].'" view_id="220"] Edit Enrollment [/gv_edit_entry_link]'); 
	}
}
}
else{
	//echo "<a href='".site_url()."/login'>Login First</a>";?>
<script>
jQuery(document).ready(function(){
window.location.href = '<?php  echo site_url(); ?>';
});

</script>
<?php
}
get_footer(); ?>