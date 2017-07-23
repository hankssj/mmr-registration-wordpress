<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>
</div><!-- .site-content -->


<script type='text/javascript' src=' https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js '></script>
<link rel='stylesheet' id='dashicons-css'  href='
    https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css
' type='text/css' media='all' />
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Primary Menu', 'twentysixteen' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'menu_class'     => 'primary-menu',
						 ) );
					?>
				</nav><!-- .main-navigation -->
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twentysixteen' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'social',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						) );
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>

			<div class="site-info">
				<?php
					/**
					 * Fires before the twentysixteen footer text for footer customization.
					 *
					 * @since Twenty Sixteen 1.0
					 */
					do_action( 'twentysixteen_credits' );
				?>
				<span class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentysixteen' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentysixteen' ), 'WordPress' ); ?></a>
			</div><!-- .site-info -->
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->
<?php wp_footer(); ?>
<?php 
$user = new WP_User(get_current_user_id());
if(trim($user->roles[0]) != 'administrator'){ ?>
<script type="text/javascript">
	// Script to hide form on submit first entry inside event table
	var validator;
	var validator_step5;
	 jQuery(document).ready(function(){
		 
      validator = jQuery( "#step3_form" ).validate({
		  rules: {
		  	instrument1 :{
		    	required: true
		    },
		    comment1: {
		      required: true
		    },
		    instrument2 :{
		    	required: true
		    },
		    comment2: {
		      required: true
		    },
		    p_instrument1: {
		      required: true
		    },
		    otherparticipant1: {
		      required: true
		    },
		    p_comment1: {
		      required: true
		    },
		    contactpersonname1 :{
		    	required: true
		    },
		    contactpersonemail1: {
		      required: true
		    },		    
		    p_instrument2: {
		      required: true
		    },
		   
		    otherparticipant2: {
		      required: true
		    },
		    p_comment2: {
		      required: true
		    },
		     
		    contactpersonname2 :{
		    	required: true
		    },
		    contactpersonemail2: {
		      required: true
		    }
		  },
		  messages: {
		  	
		    instrument1 :{
		    	 required: "Please Enter Instrument"
		    },
		    comment1: {
		      required: "Please Enter Comment"
		    },
		    instrument2 :{
		    	required: "Please Enter Instrument"
		    },
		    comment2: {
		     required: "Please Enter Comment"
		    },
		    p_instrument1: {
		      required: "Please Enter Instrument"
		    },
		    otherparticipant1: {
		      required: "Please Enter Other Participant"
		    },
		    p_comment1: {
		      required: "Please Enter Comment"
		    },
		    contactpersonname1 :{
		    	required: "Please Enter Contact Person Name"
		    },
		    contactpersonemail1: {
		      required: "Please Enter Contact Person Email"
		    },		    
		    p_instrument2: {
		      required: "Please Enter Instrument"
		    },
		    
		    otherparticipant2: {
		      required: "Please Enter Other Participant"
		    },
		    p_comment2: {
		      required: "Please Enter Comment"
		    },
		     
		    contactpersonname2 :{
		    	required: "Please Enter Contact Person Name"
		    },
		    contactpersonemail2: {
		     required: "Please Enter Contact Person Email"
		    }
		  }
		});
		
		/*validator_step5 = jQuery( "#step5_form" ).validate({
		  rules: {
		  	rate_ability :{
		    	required: true
		    },
		    groups_during_year: {
		      required: true
		    },
		    required_audition :{
		    	required: true
		    },
		    learn_peice: {
		      required: true
		    },
		    difficult_peice: {
		      required: true
		    },
		    studied_theory: {
		      required: true
		    },
		    what_year_1: {
		      required: true
		    },
		    voice_class :{
		    	required: true
		    },
		    what_year_2: {
		      required: true
		    },		    
		    voice_lessons: {
		      required: true
		    },
		   
		    what_year_3: {
		      required: true
		    },
		    singing_with_ensamble: {
		      required: true
		    },
		    sightreading_ability :{
		    	required: true
		    },
		    groups_during_year_string :{
		    	required: true
		    },
		    learn_peice_string :{
		    	required: true
		    },
		    practice_alone_reg :{
		    	required: true
		    },
		    study_privately :{
		    	required: true
		    },
		    how_many_years :{
		    	required: true
		    },
		    play_chamber_music :{
		    	required: true
		    },
		    'read_positions[]' :{
		    	required: true,
				minlength: 1
		    },
		    sightreading_ability_string :{
		    	required: true
		    },
		    chamber_music_ability :{
		    	required: true
		    },
		    large_ensemble_ability :{
		    	required: true
		    },
		    like_to_play_jazz_ensamble :{
		    	required: true
		    },
		    played_in_small_jazz_ensamble :{
		    	required: true
		    },
		    played_in_big_band :{
		    	required: true
		    },
		    practice_alone_reg_piano :{
		    	required: true
		    },
		    study_privately_piano :{
		    	required: true
		    },
		    how_many_years_piano :{
		    	required: true
		    },
		    like_to_play_jazz_ensamble_piano :{
		    	required: true
		    },
		    played_in_small_jazz_ensamble_piano :{
		    	required: true
		    },
		    played_in_big_band_piano :{
		    	required: true
		    },
		    play_chamber_music_piano :{
		    	required: true
		    },
		    list_composers_piano :{
		    	required: true
		    },
		    sightreading_ability_string_piano :{
		    	required: true
		    },
		    chamber_music_ability_piano :{
		    	required: true
		    },
		    groups_during_year_brass :{
		    	required: true
		    },
		    required_audition_brass :{
		    	required: true
		    },
		    'transpose_to_q[]' :{
		    	required: true,
				minlength: 1
		    },
		    practice_alone_reg_brass :{
		    	required: true
		    },
		    study_privately_brass :{
		    	required: true
		    },
		    how_many_years_brass :{
		    	required: true
		    },
		    play_chamber_music_brass :{
		    	required: true
		    },
		    'instrunment_bring_camp_q[]' :{
		    	required: true,
				minlength: 1
		    },
		    any_other_ins_brass :{
		    	required: true
		    },
		    sightreading_ability_string_brass :{
		    	required: true
		    },
		    chamber_music_ability_brass :{
		    	required: true
		    },
		    large_ensemble_ability_brass :{
		    	required: true
		    },
		  },
		  errorPlacement: function(error,element) {
		    return true;
		  }
		});*/
		
		
		
		
		
		jQuery('#study_privately_yes').click(function() {
		   if(jQuery('#study_privately_yes').is(':checked')) { jQuery('#how_many_years').removeAttr('disabled'); }
		   
		});
		jQuery('#study_privately_no').click(function() {
			if(jQuery('#study_privately_no').is(':checked')) { jQuery('#how_many_years').attr('disabled','disabled');  }
		});
		
		jQuery('#study_privately_yes_piano').click(function() {
		   if(jQuery('#study_privately_yes_piano').is(':checked')) { jQuery('#how_many_years_piano').removeAttr('disabled'); }
		   
		});
		jQuery('#study_privately_no_piano').click(function() {
			if(jQuery('#study_privately_no_piano').is(':checked')) { jQuery('#how_many_years_piano').attr('disabled','disabled');  }
		});
		
		jQuery('#study_privately_yes_brass').click(function() {
		   if(jQuery('#study_privately_yes_brass').is(':checked')) { jQuery('#how_many_years_brass').removeAttr('disabled'); }
		   
		});
		jQuery('#study_privately_no_brass').click(function() {
			if(jQuery('#study_privately_no_brass').is(':checked')) { jQuery('#how_many_years_brass').attr('disabled','disabled');  }
		});
       

		if(jQuery(".for-admin-only div input").val() != ''){
			jQuery(".for-admin-only div input").prop('readonly', true);
		}
		else{
			jQuery(".for-admin-only").hide();
		}
	});
</script>	<?php
}
?>
<script>
  !function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);
</script>
<?php
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->data->ID;
$results = $wpdb->get_results( 'SELECT * FROM payments WHERE user_ID = '.$user_id, ARRAY_A );
$gross_paid = '0';
foreach($results as $payment){
	$gross_paid += $payment['payment_amount'];
}
$normal_reg = get_post_meta( 218, 'normal_user_registration', true );
$normal_instrument = get_post_meta( 218, 'normal_user_instrumental', true );
$normal_double_room = get_post_meta( 218, 'normal_user_double_dorm_room', true );
$normal_single_room = get_post_meta( 218, 'normal_user_single_dorm_room', true );
$normal_all_meal = get_post_meta( 218, 'normal_user_all_meals', true );
$normal_lunch_dinner = get_post_meta( 218, 'normal_user_lunch_and_dinner_only', true );
$normal_wine = get_post_meta( 218, 'normal_user_wine_per_glass', true );
$normal_tshirt = get_post_meta( 218, 'normal_user_per_tshirt', true );

// Faculty And Staff
$faculty_staff_single_room = get_post_meta( 218, 'faculty/staff_single_dorm_room', true );
$faculty_staff_wine = get_post_meta( 218, 'faculty/staff_wine_per_glass', true );
$faculty_staff_tshirt = get_post_meta( 218, 'faculty/staff_per_tshirt', true );

// Board
$board_registration_and_instrumental = get_post_meta( 218, 'board_registration_and_instrumental', true );
$board_user_per_tshirt = get_post_meta( 218, 'board_user_per_tshirt', true );

?>

<input type="hidden" id="normal_reg" name="normal_reg" value="<?php echo $normal_reg; ?>">
<input type="hidden" id="normal_instrument" name="normal_instrument" value="<?php echo $normal_instrument; ?>">
<input type="hidden" id="normal_double_room" name="normal_double_room" value="<?php echo $normal_double_room; ?>">
<input type="hidden" id="normal_single_room" name="normal_single_room" value="<?php echo $normal_single_room; ?>">
<input type="hidden" id="normal_all_meal" name="normal_all_meal" value="<?php echo $normal_all_meal; ?>">
<input type="hidden" id="normal_lunch_dinner" name="normal_lunch_dinner" value="<?php echo $normal_lunch_dinner; ?>">
<input type="hidden" id="normal_wine" name="normal_wine" value="<?php echo $normal_wine; ?>">
<input type="hidden" id="normal_tshirt" name="normal_tshirt" value="<?php echo $normal_tshirt; ?>">

<input type="hidden" id="faculty_staff_single_room" name="faculty_staff_single_room" value="<?php echo $faculty_staff_single_room; ?>">
<input type="hidden" id="faculty_staff_wine" name="faculty_staff_wine" value="<?php echo $faculty_staff_wine; ?>">
<input type="hidden" id="faculty_staff_tshirt" name="faculty_staff_tshirt" value="<?php echo $faculty_staff_tshirt; ?>">

<input type="hidden" id="board_registration_and_instrumental" name="board_registration_and_instrumental" value="<?php echo $board_registration_and_instrumental; ?>">
<input type="hidden" id="board_user_per_tshirt" name="board_user_per_tshirt" value="<?php echo $board_user_per_tshirt; ?>">
<input type="hidden" name="grevityuserrole" id="grevityuserrole" value="">
<input type="hidden" name="gross_paid" id="gross_paid" value="<?php echo $gross_paid; ?>">

<input type="hidden" name="gross_paidadmin" id="gross_paidadmin" value="">

<script type="text/javascript">

	jQuery(document).ready(function(){

		//jQuery('#gform_submit_button_1').val('Next');
		var formID = jQuery("input[name=lid]").val();

		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				jQuery.ajax({
	                cache: false,
	                type: 'POST',
	                url: '<?php echo admin_url('admin-ajax.php'); ?>',
	                data: 'formID='+formID+'&action=get_userrole',
	                success: function(data) 
	                {
	                	jQuery('#grevityuserrole').val(data);
	               	}
	            });

	        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

			jQuery.ajax({
	            cache: false,
	            type: 'POST',
	            url: '<?php echo admin_url('admin-ajax.php'); ?>',
	            data: 'formID='+formID+'&action=get_gross',
	            success: function(data) 
	            {
	            	jQuery('#gross_paidadmin').val(data);
	           	}
	        });

	});

	<?php
	if($current_user->roles[0] == 'administrator'){ ?>
	setTimeout(function(){
		var grevityuserrole = jQuery('#grevityuserrole').val();
		if(grevityuserrole == 'subscriber'){
			
			var normal_reg = jQuery('#normal_reg').val();
			var normal_instrument = jQuery('#normal_instrument').val();
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var normal_tshirt = jQuery('#normal_tshirt').val();
			var gross_paid = jQuery('#gross_paidadmin').val();

			jQuery("#label_1_54_1").append(" ($" + normal_instrument + ")");
			jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
			jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
			jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
			jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
			jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
			jQuery("#field_1_37").append(" ($" + normal_tshirt + ") Per Tshirt");

			setInterval(function(){


			var totalfee = 0;
			totalfee = parseInt(totalfee)+parseInt(normal_reg);

			var instrumentcheck = 0;
			var double_room_check = 0;
			var single_room_check = 0;
			var full_meal_check = 0;
			var lunch_dinner_check = 0;
			var wine_check = 0;
			var tshirtcheck = 0;

			// check instrument
			if(jQuery("#choice_1_54_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_instrument);
			  	instrumentcheck = 1;
			}

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			  	double_room_check = 1;
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			  	single_room_check = 1;
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			  	full_meal_check = 1;
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			  	lunch_dinner_check = 1;
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			if(isNaN(wineglass)) {
				wineglass = 0;
			}
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			if(wineglass > 0){
				wine_check = 1;
			}

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			if(isNaN(small)) {
				small = 0;
			}
			if(isNaN(medium)) {
				medium = 0;
			}
			if(isNaN(large)) {
				large = 0;
			}
			if(isNaN(xl)) {
				xl = 0;
			}
			if(isNaN(xxl)) {
				xxl = 0;
			}
			if(isNaN(xxxl)) {
				xxxl = 0;
			}
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			if(totalshirt > 0){
				tshirtcheck = 1;
			}
			var shirttotal = parseInt(normal_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);

			var donation = jQuery('#input_1_35').val();
			donationdata = '';

			if(donation != 'none'){
				donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
				totalfee = parseInt(totalfee)+parseInt(donation);
			}

			var instrumentdata = '';

			if(instrumentcheck == 1){
				instrumentdata =  "<br>" +
				jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")";
			}

			var double_room_data = '';

			if(double_room_check == 1){
				double_room_data =  "<br>" +
				jQuery('#label_1_55_0').html();
			}

			var single_room_data = '';

			if(single_room_check == 1){
				single_room_data =  "<br>" +
				jQuery('#label_1_55_1').html();
			}

			var full_meal_data = '';

			if(full_meal_check == 1){
				full_meal_data =  "<br>" +
				jQuery('#label_1_56_0').html();
			}

			var lunch_dinner_data = '';

			if(lunch_dinner_check == 1){
				lunch_dinner_data =  "<br>" +
				jQuery('#label_1_56_1').html();
			}

			var wine_data = '';

			if(wine_check == 1){
				wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal;
			}

			var tshirt_data = '';
			var regfee = '';
			
			if(tshirtcheck == 1){
				tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + normal_tshirt + " = $" + shirttotal;
			}

			var regfee = "Registration Fees ($" + normal_reg + ")";
			var due = totalfee - gross_paid;

			jQuery('.custotal-amount').html(regfee + instrumentdata + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);

			jQuery('#amount').val(totalfee);

			}, 500);
		
		}

		if(grevityuserrole == 'faculty' || grevityuserrole == 'staff'){

			var faculty_staff_single_room = jQuery('#faculty_staff_single_room').val();
			var faculty_staff_wine = jQuery('#faculty_staff_wine').val();
			var faculty_staff_tshirt = jQuery('#faculty_staff_tshirt').val();
			var gross_paid = jQuery('#gross_paidadmin').val();

			jQuery("#label_1_55_1").append(" ($" + faculty_staff_single_room + ")");
			jQuery("#field_1_36 .gfield_label").append(" ($" + faculty_staff_wine + ") Per Glass");
			jQuery("#field_1_37").append(" ($" + faculty_staff_tshirt + ") Per Tshirt");


			setInterval(function(){
				
				var totalfee = 0;
				var single_room_check = 0;
				var wine_check = 0;
				var tshirtcheck = 0;
				
				// check Single dorm room
				if(jQuery("#choice_1_55_1").is(":checked")) {
				  	totalfee = parseInt(totalfee)+parseInt(faculty_staff_single_room);
				  	single_room_check = 1;
				}

				// check Wine Glass
				var wineglass = jQuery("#input_1_36 ").val();
				if(isNaN(wineglass)) {
					wineglass = 0;
				}
				var winetotal = parseInt(faculty_staff_wine*wineglass);
				totalfee = parseInt(totalfee)+parseInt(winetotal);

				if(wineglass > 0){
					wine_check = 1;
				}

				// check TShirt
				var small = parseInt(jQuery("#input_1_38 ").val());
				var medium = parseInt(jQuery("#input_1_39 ").val());
				var large = parseInt(jQuery("#input_1_40 ").val());
				var xl = parseInt(jQuery("#input_1_41 ").val());
				var xxl = parseInt(jQuery("#input_1_42 ").val());
				var xxxl = parseInt(jQuery("#input_1_43 ").val());
				if(isNaN(small)) {
					small = 0;
				}
				if(isNaN(medium)) {
					medium = 0;
				}
				if(isNaN(large)) {
					large = 0;
				}
				if(isNaN(xl)) {
					xl = 0;
				}
				if(isNaN(xxl)) {
					xxl = 0;
				}
				if(isNaN(xxxl)) {
					xxxl = 0;
				}

				var totalshirt = small+medium+large+xl+xxl+xxxl;
				var shirttotal = parseInt(faculty_staff_tshirt*totalshirt);
				if(totalshirt > 1){
					tshirtcheck = 1;
				}
				
				if(totalshirt >= 1){
					totalshirt = parseInt(totalshirt)-1;
					shirttotal = parseInt(shirttotal)-parseInt(faculty_staff_tshirt);
					
				}

				var single_room_data = '';

				if(single_room_check == 1){
					single_room_data =  "<br>" +
					jQuery('#label_1_55_1').html();
				}

				var wine_data = '';

				if(wine_check == 1){
					wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + faculty_staff_wine + " = $" + winetotal;
				}

				var tshirt_data = '';

				if(tshirtcheck == 1){
					tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + faculty_staff_tshirt + " = $" + shirttotal;
				}

				totalfee = parseInt(totalfee)+parseInt(shirttotal);
				
				var donation = jQuery('#input_1_35').val();
				donationdata = '';

				if(donation != 'none'){
					donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
					totalfee = parseInt(totalfee)+parseInt(donation);
				}
				var due = totalfee - gross_paid;

				jQuery('.custotal-amount').html( single_room_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);

				jQuery('#amount').val(totalfee);

			}, 500);
		}
		if(grevityuserrole == 'board'){

			var board_registration_and_instrumental = jQuery('#board_registration_and_instrumental').val();
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var board_user_per_tshirt = jQuery('#board_user_per_tshirt').val();
			var gross_paid = jQuery('#gross_paidadmin').val();

			jQuery("#label_1_54_1").append(" ($" + board_registration_and_instrumental + ")");
			jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
			jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
			jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
			jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
			jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
			jQuery("#field_1_37").append(" ($" + board_user_per_tshirt + ") Per Tshirt");

			setInterval(function(){

					var totalfee = 0;
					var instrumentcheck = 0;
					var double_room_check = 0;
					var single_room_check = 0;
					var full_meal_check = 0;
					var lunch_dinner_check = 0;
					var wine_check = 0;
					var tshirtcheck = 0;


					// check instrument
					if(jQuery("#choice_1_54_1").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(board_registration_and_instrumental);
					  	instrumentcheck = 1;
					}

					// check Double dorm room
					if(jQuery("#choice_1_55_0").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
					  	double_room_check = 1;
					}

					// check Single dorm room
					if(jQuery("#choice_1_55_1").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
					  	single_room_check = 1;
					}

					// check All Meals
					if(jQuery("#choice_1_56_0").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
					  	full_meal_check = 1;
					}

					// check Lunch and Dinner Only
					if(jQuery("#choice_1_56_1").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
					  	lunch_dinner_check = 1;
					}

					// check Wine Glass
					var wineglass = jQuery("#input_1_36 ").val();
					if(isNaN(wineglass)) {
						wineglass = 0;
					}
					var winetotal = parseInt(normal_wine*wineglass);
					totalfee = parseInt(totalfee)+parseInt(winetotal);
					if(wineglass > 0){
						wine_check = 1;
					}

					// check TShirt
					var small = parseInt(jQuery("#input_1_38 ").val());
					var medium = parseInt(jQuery("#input_1_39 ").val());
					var large = parseInt(jQuery("#input_1_40 ").val());
					var xl = parseInt(jQuery("#input_1_41 ").val());
					var xxl = parseInt(jQuery("#input_1_42 ").val());
					var xxxl = parseInt(jQuery("#input_1_43 ").val());
					if(isNaN(small)) {
						small = 0;
					}
					if(isNaN(medium)) {
						medium = 0;
					}
					if(isNaN(large)) {
						large = 0;
					}
					if(isNaN(xl)) {
						xl = 0;
					}
					if(isNaN(xxl)) {
						xxl = 0;
					}
					if(isNaN(xxxl)) {
						xxxl = 0;
					}
					var totalshirt = small+medium+large+xl+xxl+xxxl;
					var shirttotal = parseInt(board_user_per_tshirt*totalshirt);
					if(totalshirt > 1){
						tshirtcheck = 1;
					}
					
					if(totalshirt >= 1){
						totalshirt = parseInt(totalshirt)-1;
						shirttotal = parseInt(shirttotal)-parseInt(board_user_per_tshirt);
					}

					totalfee = parseInt(totalfee)+parseInt(shirttotal);

					var donation = jQuery('#input_1_35').val();
					donationdata = '';

					if(donation != 'none'){
						donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
						totalfee = parseInt(totalfee)+parseInt(donation);
					}


					var instrumentdata = '';
					var regfee = '';

					var regfee = "Registration Fees + instrumental Fees ($" + board_registration_and_instrumental + ")";

					if(instrumentcheck == 1){
						instrumentdata =  "<br>" +
					jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")";

						
					}

					var double_room_data = '';

					if(double_room_check == 1){
						double_room_data =  "<br>" +
						jQuery('#label_1_55_0').html();
					}

					var single_room_data = '';

					if(single_room_check == 1){
						single_room_data =  "<br>" +
						jQuery('#label_1_55_1').html();
					}

					var full_meal_data = '';

					if(full_meal_check == 1){
						full_meal_data =  "<br>" +
						jQuery('#label_1_56_0').html();
					}

					var lunch_dinner_data = '';

					if(lunch_dinner_check == 1){
						lunch_dinner_data =  "<br>" +
						jQuery('#label_1_56_1').html();
					}

					var wine_data = '';

					if(wine_check == 1){
						wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal;
					}

					var tshirt_data = '';

					if(tshirtcheck == 1){
						tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + board_user_per_tshirt + " = $" + shirttotal;
					}

					var due = totalfee - gross_paid;

					jQuery('.custotal-amount').html(regfee + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);

					jQuery('#amount').val(totalfee);

			}, 500);
		}

		if(grevityuserrole == 'volunteer'){
				
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var normal_tshirt = jQuery('#normal_tshirt').val();
			var gross_paid = jQuery('#gross_paidadmin').val();
			
			jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
			jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
			jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
			jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
			jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
			jQuery("#field_1_37").append(" ($" + normal_tshirt + ") Per Tshirt");

				setInterval(function(){
					
					var totalfee = 0;
					var instrumentcheck = 0;
					var double_room_check = 0;
					var single_room_check = 0;
					var full_meal_check = 0;
					var lunch_dinner_check = 0;
					var wine_check = 0;
					var tshirtcheck = 0;

					// check Double dorm room
					if(jQuery("#choice_1_55_0").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
					  	double_room_check = 1;
					}

					// check Single dorm room
					if(jQuery("#choice_1_55_1").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
					  	single_room_check = 1;
					}

					// check All Meals
					if(jQuery("#choice_1_56_0").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
					  	full_meal_check = 1;
					}

					// check Lunch and Dinner Only
					if(jQuery("#choice_1_56_1").is(":checked")) {
					  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
					  	lunch_dinner_check = 1;
					}

					// check Wine Glass
					var wineglass = jQuery("#input_1_36 ").val();
					if(isNaN(wineglass)) {
						wineglass = 0;
					}
					var winetotal = parseInt(normal_wine*wineglass);
					totalfee = parseInt(totalfee)+parseInt(winetotal);
					if(wineglass > 0){
						wine_check = 1;
					}


					// check TShirt
					var small = parseInt(jQuery("#input_1_38 ").val());
					var medium = parseInt(jQuery("#input_1_39 ").val());
					var large = parseInt(jQuery("#input_1_40 ").val());
					var xl = parseInt(jQuery("#input_1_41 ").val());
					var xxl = parseInt(jQuery("#input_1_42 ").val());
					var xxxl = parseInt(jQuery("#input_1_43 ").val());
					if(isNaN(small)) {
						small = 0;
					}
					if(isNaN(medium)) {
						medium = 0;
					}
					if(isNaN(large)) {
						large = 0;
					}
					if(isNaN(xl)) {
						xl = 0;
					}
					if(isNaN(xxl)) {
						xxl = 0;
					}
					if(isNaN(xxxl)) {
						xxxl = 0;
					}
					var totalshirt = small+medium+large+xl+xxl+xxxl;
					var shirttotal = parseInt(normal_tshirt*totalshirt);
					totalfee = parseInt(totalfee)+parseInt(shirttotal);
					if(totalshirt > 0){
						tshirtcheck = 1;
					}

					var donation = jQuery('#input_1_35').val();
					donationdata = '';

					if(donation != 'none'){
						donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
						totalfee = parseInt(totalfee)+parseInt(donation);
					}

					var instrumentdata = '';

					if(instrumentcheck == 1){
						instrumentdata =  "<br>" +
					jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")";
					}

					var double_room_data = '';

					if(double_room_check == 1){
						double_room_data =  "<br>" +
						jQuery('#label_1_55_0').html();
					}

					var single_room_data = '';

					if(single_room_check == 1){
						single_room_data =  "<br>" +
						jQuery('#label_1_55_1').html();
					}

					var full_meal_data = '';

					if(full_meal_check == 1){
						full_meal_data =  "<br>" +
						jQuery('#label_1_56_0').html();
					}

					var lunch_dinner_data = '';

					if(lunch_dinner_check == 1){
						lunch_dinner_data =  "<br>" +
						jQuery('#label_1_56_1').html();
					}

					var wine_data = '';

					if(wine_check == 1){
						wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal;
					}
					
					var tshirt_data = '';
					
					if(tshirtcheck == 1){
						tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + normal_tshirt + " = $" + shirttotal;
					}

					var due = totalfee - gross_paid;

					jQuery('.custotal-amount').html(double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);
	  
					jQuery('#amount').val(totalfee);

				}, 500);
			}
		
	 }, 1000);
		
	<?php }
	// For Admin
	
	// If user role : subscriber
	if($current_user->roles[0] == 'subscriber'  ){
	?>
		var normal_reg = jQuery('#normal_reg').val();
		var normal_instrument = jQuery('#normal_instrument').val();
		var normal_double_room = jQuery('#normal_double_room').val();
		var normal_single_room = jQuery('#normal_single_room').val();
		var normal_all_meal = jQuery('#normal_all_meal').val();
		var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
		var normal_wine = jQuery('#normal_wine').val();
		var normal_tshirt = jQuery('#normal_tshirt').val();
		var gross_paid = jQuery('#gross_paid').val();

		jQuery("#label_1_54_1").append(" ($" + normal_instrument + ")");
		jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
		jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
		jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
		jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
		jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
		jQuery("#field_1_37").append(" ($" + normal_tshirt + ") Per Tshirt");

		setInterval(function(){
			
			var totalfee = 0;
			totalfee = parseInt(totalfee)+parseInt(normal_reg);

			var instrumentcheck = 0;
			var double_room_check = 0;
			var single_room_check = 0;
			var full_meal_check = 0;
			var lunch_dinner_check = 0;
			var wine_check = 0;
			var tshirtcheck = 0;

			// check instrument
			if(jQuery("#choice_1_54_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_instrument);
			  	instrumentcheck = 1;
			}

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			  	double_room_check = 1;
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			  	single_room_check = 1;
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			  	full_meal_check = 1;
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			  	lunch_dinner_check = 1;
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			if(isNaN(wineglass)) {
				wineglass = 0;
			}
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			if(wineglass > 0){
				wine_check = 1;
			}

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			if(isNaN(small)) {
				small = 0;
			}
			if(isNaN(medium)) {
				medium = 0;
			}
			if(isNaN(large)) {
				large = 0;
			}
			if(isNaN(xl)) {
				xl = 0;
			}
			if(isNaN(xxl)) {
				xxl = 0;
			}
			if(isNaN(xxxl)) {
				xxxl = 0;
			}
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			if(totalshirt > 0){
				tshirtcheck = 1;
			}
			var shirttotal = parseInt(normal_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);

			var donation = jQuery('#input_1_35').val();
			donationdata = '';

			if(donation != 'none'){
				donationdata =  "<tr><td>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")</td></tr>";
				totalfee = parseInt(totalfee)+parseInt(donation);
			}

			var instrumentdata = '';

			if(instrumentcheck == 1){
				instrumentdata =  "<tr><td>" +
				jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")</td></tr>";
			}

			var double_room_data = '';

			if(double_room_check == 1){
				double_room_data =  "<tr><td>" +
				jQuery('#label_1_55_0').html()+ "</td></tr>";
			}

			var single_room_data = '';

			if(single_room_check == 1){
				single_room_data =  "<tr><td>" +
				jQuery('#label_1_55_1').html()+ "</td></tr>";
			}

			var full_meal_data = '';

			if(full_meal_check == 1){
				full_meal_data =  "<tr><td>" +
				jQuery('#label_1_56_0').html()+ "</td></tr>";
			}

			var lunch_dinner_data = '';

			if(lunch_dinner_check == 1){
				lunch_dinner_data =  "<tr><td>" +
				jQuery('#label_1_56_1').html()+ "</td></tr>";
			}

			var wine_data = '';

			if(wine_check == 1){
				wine_data =  "<tr><td> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal+ "</td></tr>";
			}
			
			var tshirt_data = '';
			
			if(tshirtcheck == 1){
				tshirt_data =  "<tr><td> Total T-Shirt : " + totalshirt + "*" + normal_tshirt + " = $" + shirttotal+ "</td></tr>";
			}

			var regfee = "<tr><td>Registration Fees ($" + normal_reg + ")</td></tr>";

			var due = totalfee - gross_paid;
			
			jQuery('.custotal-amount').html("<table class='custom-enroll'>" + regfee + instrumentdata + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<tr><td> Total : $" +  totalfee + "</td></tr><tr><td> Paid : $" + gross_paid + "</td></tr><tr><td> Due : $" + due + "</td></tr></table>");    

			jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : subscriber

	// If user role : Faculty and Staff
	if($current_user->roles[0] == 'faculty' || $current_user->roles[0] == 'staff'){
	?>
		var faculty_staff_single_room = jQuery('#faculty_staff_single_room').val();
		var faculty_staff_wine = jQuery('#faculty_staff_wine').val();
		var faculty_staff_tshirt = jQuery('#faculty_staff_tshirt').val();
		var gross_paid = jQuery('#gross_paid').val();

		jQuery("#label_1_55_1").append(" ($" + faculty_staff_single_room + ")");
		jQuery("#field_1_36 .gfield_label").append(" ($" + faculty_staff_wine + ") Per Glass");
		jQuery("#field_1_37").append(" ($" + faculty_staff_tshirt + ") Per Tshirt");

		setInterval(function(){
			
			var totalfee = 0;

			var single_room_check = 0;
			var wine_check = 0;
			var tshirtcheck = 0;
			
			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(faculty_staff_single_room);
			  	single_room_check = 1;
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			if(isNaN(wineglass)) {
				wineglass = 0;
			}
			var winetotal = parseInt(faculty_staff_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			if(wineglass > 0){
				wine_check = 1;
			}

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			if(isNaN(small)) {
				small = 0;
			}
			if(isNaN(medium)) {
				medium = 0;
			}
			if(isNaN(large)) {
				large = 0;
			}
			if(isNaN(xl)) {
				xl = 0;
			}
			if(isNaN(xxl)) {
				xxl = 0;
			}
			if(isNaN(xxxl)) {
				xxxl = 0;
			}
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(faculty_staff_tshirt*totalshirt);
			if(totalshirt > 1){
				tshirtcheck = 1;
			}
			
			if(totalshirt >= 1){
				totalshirt = parseInt(totalshirt)-1;
				shirttotal = parseInt(shirttotal)-parseInt(faculty_staff_tshirt);
				
			}
			var single_room_data = '';

			if(single_room_check == 1){
				single_room_data =  "<br>" +
				jQuery('#label_1_55_1').html();
			}

			var wine_data = '';

			if(wine_check == 1){
				wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + faculty_staff_wine + " = $" + winetotal;
			}

			var tshirt_data = '';

			if(tshirtcheck == 1){
				tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + faculty_staff_tshirt + " = $" + shirttotal;
			}

			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			
			var donation = jQuery('#input_1_35').val();
			donationdata = '';

			if(donation != 'none'){
				donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
				totalfee = parseInt(totalfee)+parseInt(donation);
			}
			var due = totalfee - gross_paid;

			jQuery('.custotal-amount').html( single_room_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);

			jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : Faculty and Staff

	// If user role : Board
	if($current_user->roles[0] == 'board'){
	?>
	var board_registration_and_instrumental = jQuery('#board_registration_and_instrumental').val();
	var normal_double_room = jQuery('#normal_double_room').val();
	var normal_single_room = jQuery('#normal_single_room').val();
	var normal_all_meal = jQuery('#normal_all_meal').val();
	var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
	var normal_wine = jQuery('#normal_wine').val();
	var board_user_per_tshirt = jQuery('#board_user_per_tshirt').val();
	var gross_paid = jQuery('#gross_paid').val();

	jQuery("#label_1_54_1").append(" ($" + board_registration_and_instrumental + ")");
	jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
	jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
	jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
	jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
	jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
	jQuery("#field_1_37").append(" ($" + board_user_per_tshirt + ") Per Tshirt");

		setInterval(function(){

			var totalfee = 0;
			var instrumentcheck = 0;
			var double_room_check = 0;
			var single_room_check = 0;
			var full_meal_check = 0;
			var lunch_dinner_check = 0;
			var wine_check = 0;
			var tshirtcheck = 0;

			// check instrument
			if(jQuery("#choice_1_54_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(board_registration_and_instrumental);
			  	instrumentcheck = 1;
			}
			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			  	double_room_check = 1;
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			  	single_room_check = 1;
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			  	full_meal_check = 1;
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			  	lunch_dinner_check = 1;
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			if(isNaN(wineglass)) {
				wineglass = 0;
			}

			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);
			if(wineglass > 0){
				wine_check = 1;
			}

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			
			if(isNaN(small)) {
				small = 0;
			}
			if(isNaN(medium)) {
				medium = 0;
			}
			if(isNaN(large)) {
				large = 0;
			}
			if(isNaN(xl)) {
				xl = 0;
			}
			if(isNaN(xxl)) {
				xxl = 0;
			}
			if(isNaN(xxxl)) {
				xxxl = 0;
			}

			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(board_user_per_tshirt*totalshirt);
						
			if(totalshirt > 1){
				tshirtcheck = 1;
			}
				
			if(totalshirt >= 1){
				totalshirt = parseInt(totalshirt)-1;
				shirttotal = parseInt(shirttotal)-parseInt(board_user_per_tshirt);
			}

			totalfee = parseInt(totalfee)+parseInt(shirttotal);

			var donation = jQuery('#input_1_35').val();
			donationdata = '';

			if(donation != 'none'){
				donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
				totalfee = parseInt(totalfee)+parseInt(donation);
			}

			var instrumentdata = '';
			var regfee = '';
			var regfee = "Registration Fees + instrumental Fees ($" + board_registration_and_instrumental + ")";

			if(instrumentcheck == 1){
				instrumentdata =  "<br>" +
				jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")";
				
				
			}

			var double_room_data = '';

			if(double_room_check == 1){
				double_room_data =  "<br>" +
				jQuery('#label_1_55_0').html();
			}

			var single_room_data = '';

			if(single_room_check == 1){
				single_room_data =  "<br>" +
				jQuery('#label_1_55_1').html();
			}

			var full_meal_data = '';

			if(full_meal_check == 1){
				full_meal_data =  "<br>" +
				jQuery('#label_1_56_0').html();
			}

			var lunch_dinner_data = '';

			if(lunch_dinner_check == 1){
				lunch_dinner_data =  "<br>" +
				jQuery('#label_1_56_1').html();
			}

			var wine_data = '';

			if(wine_check == 1){
				wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal;
			}

			var tshirt_data = '';

			if(tshirtcheck == 1){
				tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + board_user_per_tshirt + " = $" + shirttotal;
			}
			
			var due = totalfee - gross_paid;

			jQuery('.custotal-amount').html(regfee + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);

			jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : Board

	// If user role : Volunteer
	if($current_user->roles[0] == 'volunteer'){
	?>	
		var normal_double_room = jQuery('#normal_double_room').val();
		var normal_single_room = jQuery('#normal_single_room').val();
		var normal_all_meal = jQuery('#normal_all_meal').val();
		var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
		var normal_wine = jQuery('#normal_wine').val();
		var normal_tshirt = jQuery('#normal_tshirt').val();
		var gross_paid = jQuery('#gross_paid').val();

		jQuery("#label_1_55_0").append(" ($" + normal_double_room + ")");
		jQuery("#label_1_55_1").append(" ($" + normal_single_room + ")");
		jQuery("#label_1_56_0").append(" ($" + normal_all_meal + ")");
		jQuery("#label_1_56_1").append(" ($" + normal_lunch_dinner + ")");
		jQuery("#field_1_36 .gfield_label").append(" ($" + normal_wine + ") Per Glass");
		jQuery("#field_1_37").append(" ($" + normal_tshirt + ") Per Tshirt");

		setInterval(function(){
			
			var totalfee = 0;
			var instrumentcheck = 0;
			var double_room_check = 0;
			var single_room_check = 0;
			var full_meal_check = 0;
			var lunch_dinner_check = 0;
			var wine_check = 0;
			var tshirtcheck = 0;

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			  	double_room_check = 1;
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			  	single_room_check = 1;
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			  	full_meal_check = 1;
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			  	lunch_dinner_check = 1;
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			if(isNaN(wineglass)) {
				wineglass = 0;
			}
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);
			if(wineglass > 0){
				wine_check = 1;
			}


			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());

			if(isNaN(small)) {
				small = 0;
			}
			if(isNaN(medium)) {
				medium = 0;
			}
			if(isNaN(large)) {
				large = 0;
			}
			if(isNaN(xl)) {
				xl = 0;
			}
			if(isNaN(xxl)) {
				xxl = 0;
			}
			if(isNaN(xxxl)) {
				xxxl = 0;
			}

			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(normal_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			if(totalshirt > 0){
				tshirtcheck = 1;
			}

			var donation = jQuery('#input_1_35').val();
			donationdata = '';

			if(donation != 'none'){
				donationdata =  "<br>" + jQuery('#field_1_35 .gfield_label').text() + " ($" + jQuery('#input_1_35').val() + ")";
				totalfee = parseInt(totalfee)+parseInt(donation);
			}

			var instrumentdata = '';

			if(instrumentcheck == 1){
				instrumentdata =  "<br>" +
				jQuery('#label_1_54_1').html() + " (" + jQuery('#input_1_23').val() + ")";
			}

			var double_room_data = '';

			if(double_room_check == 1){
				double_room_data =  "<br>" +
				jQuery('#label_1_55_0').html();
			}

			var single_room_data = '';

			if(single_room_check == 1){
				single_room_data =  "<br>" +
				jQuery('#label_1_55_1').html();
			}

			var full_meal_data = '';

			if(full_meal_check == 1){
				full_meal_data =  "<br>" +
				jQuery('#label_1_56_0').html();
			}

			var lunch_dinner_data = '';

			if(lunch_dinner_check == 1){
				lunch_dinner_data =  "<br>" +
				jQuery('#label_1_56_1').html();
			}

			var wine_data = '';

				if(wine_check == 1){
					wine_data =  "<br> Total Wine Glass : " + wineglass + "*" + normal_wine + " = $" + winetotal;
				}
				
				var tshirt_data = '';
				
				if(tshirtcheck == 1){
					tshirt_data =  "<br> Total T-Shirt : " + totalshirt + "*" + normal_tshirt + " = $" + shirttotal;
				}
				var due = totalfee - gross_paid;

				jQuery('.custotal-amount').html(double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee + "<br> Paid : $" + gross_paid + "<br> Due : $" + due);
  
				jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : subscriber

if($current_user->roles[0] != 'administrator'){
	?>
	jQuery(document).ready(function(){
		jQuery('#wpadminbar').hide();
	});
	
	<?php
}
?>
	// Script to hide form on submit first entry inside event table
	function deletefun(delID){
			var isGood=confirm('Are you sure you want to delete this enrollment?');
		    if (isGood) {
		     	jQuery('.deleteid').val(delID);
				jQuery('#deleteform').submit();
		    } else {
		      	return false;
		    }

		}

		function deletetranscation(delID){

			var isGood=confirm('Are You Sure You Want To Delete This Transcation');
		    if (isGood) {
		     	jQuery('.deleteid').val(delID);
				jQuery('#deleteform').submit();
		    } else {
		      	return false;
		    }
		}
		
	jQuery(window).scroll(function() {    
		var scroll = jQuery(window).scrollTop();
		//>=, not <=
		// if (scroll >= 150) {
		// //clearHeader, not clearheader - caps H
		// jQuery(".custom-scroll").addClass("darkHeader");
		// }
		// else{
		// 		jQuery(".custom-scroll").removeClass("darkHeader");
		// }
	}); //missing );

 setInterval(function(){ 
 if(jQuery('#custompay').is(':checked')){
				jQuery(".custompay").css("display", "block");
			}
			 }, 500);

	jQuery(document).ready(function(){
			var chamber_assemble_select = jQuery("#chamber_assemble").val();
			if(chamber_assemble_select == '1'){
				jQuery(".0").css("display", "block");
				jQuery(".1").css("display", "block");
				jQuery(".2").css("display", "none");
			}else if(chamber_assemble_select == '2'){
				jQuery(".0").css("display", "block");
				jQuery(".1").css("display", "none");
				jQuery(".2").css("display", "none");
			}

		jQuery( "#chamber_assemble" ).change(function() {
			var chamber_assemble =   jQuery( "#chamber_assemble" ).val();
			var prearranged_groupall =   jQuery( "#prearranged_groupall" ).val();
			if(chamber_assemble == 1){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment2").prop('required',false);

				jQuery(".table3").css("display", "block");
				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "none");	
				jQuery(".assignedchamber2").css("display", "none");
			}
			else if(chamber_assemble == 2){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',true);
				// jQuery("#comment2").prop('required',true);

				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".table3").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "block");
				jQuery(".assignedchamber2").css("display", "block");
			}
			else{
				validator.resetForm();
				jQuery(".parentheader").css("display", "none");
				jQuery(".chamber_assembleappend1").css("display", "none");

				// Validation
				// jQuery("#instrument1").prop('required',false);
				// jQuery("#comment1").prop('required',false);
				
				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment3").prop('required',false);

				jQuery(".assignedchamber1").css("display", "none");
				jQuery(".table3").css("display", "none");
				jQuery(".chamber_assembleappend2").css("display", "none");
				jQuery(".assignedchamber2").css("display", "none");
			}

			if(prearranged_groupall == 1){
				jQuery(".prearranged_groupall1").css("display", "block");

				jQuery(".prearranged_groupall2").css("display", "none");

				// validation
				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);

				

				// jQuery("#p_comment1").prop('required',true);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);

			}
			else if(prearranged_groupall == 2){
				jQuery(".prearranged_groupall1").css("display", "block");
				jQuery(".prearranged_groupall2").css("display", "block");

				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);
				
				// jQuery("#p_comment1").prop('required',true);
				// jQuery("#p_instrument2").prop('required',true);
				// jQuery("#contactpersonname2").prop('required',true);
				// jQuery("#contactpersonemail2").prop('required',true);
				

				// jQuery("#p_comment2").prop('required',true);
			}
			else{
				validator.resetForm();    
				jQuery(".prearranged_groupall1").css("display", "none");
				jQuery(".prearranged_groupall2").css("display", "none");

				// jQuery("#p_instrument1").prop('required',false);
				// jQuery("#contactpersonname1").prop('required',false);
				// jQuery("#contactpersonemail1").prop('required',false);
				// jQuery("#p_comment1").prop('required',false);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);
			}
			var chamber_assemble = this.value;
			var prearranged_groupall = jQuery("#prearranged_groupall").val();

			if((chamber_assemble == '0'  && prearranged_groupall == '0') || (chamber_assemble == ''  && prearranged_groupall == '') || (chamber_assemble == ''  && prearranged_groupall == '0') || (prearranged_groupall == ''  && chamber_assemble == '0')){
				jQuery('#nextensemble').prop('disabled',true);
				jQuery('#step3_submit').prop('disabled',false);
			} else {
				jQuery('#nextensemble').prop('disabled',false);
				jQuery('#step3_submit').prop('disabled',false);
			}
			if(chamber_assemble == '' || chamber_assemble == '0'){
				
				jQuery(".0").css("display", "block");
				jQuery(".1").css("display", "block");
				jQuery(".2").css("display", "block");
			}
			else if(chamber_assemble == '1'){
				
				jQuery(".0").css("display", "block");
				jQuery(".1").css("display", "block");
				jQuery(".2").css("display", "none");
			}
			else if(chamber_assemble == '2'){
				
				jQuery(".0").css("display", "block");
				jQuery(".1").css("display", "none");
				jQuery(".2").css("display", "none");
			}
		});

		var prearranged_groupall_select = jQuery("#prearranged_groupall").val();
		if(prearranged_groupall_select == '1'){
			jQuery(".c_0").css("display", "block");
			jQuery(".c_1").css("display", "block");
			jQuery(".c_2").css("display", "none");
		}else if(prearranged_groupall_select == '2'){
			jQuery(".c_0").css("display", "block");
			jQuery(".c_1").css("display", "none");
			jQuery(".c_2").css("display", "none");
		}

		jQuery( "#prearranged_groupall" ).change(function() {
			var prearranged_groupall = this.value;
			var chamber_assemble = jQuery("#chamber_assemble").val();
			var chamber_assemble =   jQuery( "#chamber_assemble" ).val();
			var prearranged_groupall =   jQuery( "#prearranged_groupall" ).val();
			if(chamber_assemble == 1){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment2").prop('required',false);

				jQuery(".table3").css("display", "block");
				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "none");	
				jQuery(".assignedchamber2").css("display", "none");
			}
			else if(chamber_assemble == 2){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',true);
				// jQuery("#comment2").prop('required',true);

				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".table3").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "block");
				jQuery(".assignedchamber2").css("display", "block");
			}
			else{
				validator.resetForm();
				jQuery(".parentheader").css("display", "none");
				jQuery(".chamber_assembleappend1").css("display", "none");

				// Validation
				// jQuery("#instrument1").prop('required',false);
				// jQuery("#comment1").prop('required',false);
				
				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment3").prop('required',false);

				jQuery(".assignedchamber1").css("display", "none");
				jQuery(".table3").css("display", "none");
				jQuery(".chamber_assembleappend2").css("display", "none");
				jQuery(".assignedchamber2").css("display", "none");
			}

			if(prearranged_groupall == 1){
				jQuery(".prearranged_groupall1").css("display", "block");

				jQuery(".prearranged_groupall2").css("display", "none");

				// validation
				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);

				

				// jQuery("#p_comment1").prop('required',true);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);

			}
			else if(prearranged_groupall == 2){
				jQuery(".prearranged_groupall1").css("display", "block");
				jQuery(".prearranged_groupall2").css("display", "block");

				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);
				
				// jQuery("#p_comment1").prop('required',true);
				// jQuery("#p_instrument2").prop('required',true);
				// jQuery("#contactpersonname2").prop('required',true);
				// jQuery("#contactpersonemail2").prop('required',true);
				

				// jQuery("#p_comment2").prop('required',true);
			}
			else{
				validator.resetForm();    
				jQuery(".prearranged_groupall1").css("display", "none");
				jQuery(".prearranged_groupall2").css("display", "none");

				// jQuery("#p_instrument1").prop('required',false);
				// jQuery("#contactpersonname1").prop('required',false);
				// jQuery("#contactpersonemail1").prop('required',false);
				// jQuery("#p_comment1").prop('required',false);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);
			}

			if((chamber_assemble == '0'  && prearranged_groupall == '0') || (chamber_assemble == ''  && prearranged_groupall == '') || (chamber_assemble == ''  && prearranged_groupall == '0') || (prearranged_groupall == ''  && chamber_assemble == '0')){
				jQuery('#nextensemble').prop('disabled',true);
				jQuery('#step3_submit').prop('disabled',false);
			} else {
				jQuery('#nextensemble').prop('disabled',false);
				jQuery('#step3_submit').prop('disabled',false);
			}


			if(prearranged_groupall == '' || prearranged_groupall == '0'){
				
				jQuery(".c_0").css("display", "block");
				jQuery(".c_1").css("display", "block");
				jQuery(".c_2").css("display", "block");
			}
			else if(prearranged_groupall == '1'){
				
				jQuery(".c_0").css("display", "block");
				jQuery(".c_1").css("display", "block");
				jQuery(".c_2").css("display", "none");
			}
			else if(prearranged_groupall == '2'){
				
				jQuery(".c_0").css("display", "block");
				jQuery(".c_1").css("display", "none");
				jQuery(".c_2").css("display", "none");
			}
		});

		jQuery( "#instrument1" ).change(function() {
			var instrument1 = this.value;
			if(instrument1 == 'Trumpet' || instrument1 == 'Horn' || instrument1 == 'Trombone' || instrument1 == 'Tuba' || instrument1 == 'Percussion' || instrument1 == 'Saxophone' || instrument1 == 'Saxophone-Soprano' || instrument1 == 'Saxophone-Alto' || instrument1 == 'Saxophone-Tenor' || instrument1 == 'Saxophone-Baritone' || instrument1 == 'Clarinet' || instrument1 == '' || instrument1 == 'Double Bass' || instrument1 == 'Piano'){
				jQuery("#instrumentyesno1").css("display", "block");
			}
			else{
				jQuery("#instrumentyesno1").css("display", "none");
			}
		});

		jQuery( "#instrument2" ).change(function() {
            var instrument2 = this.value;
            if(instrument2 == 'Trumpet' || instrument2 == 'Horn' || instrument2 == 'Trombone' || instrument2 == 'Tuba' || instrument2 == 'Percussion' || instrument2 == 'Saxophone' || instrument2 == 'Saxophone-Soprano' || instrument2 == 'Saxophone-Alto' || instrument2 == 'Saxophone-Tenor' || instrument2 == 'Saxophone-Baritone' || instrument2 == 'Clarinet' || instrument2 == '' || instrument2 == 'Double Bass' || instrument2 == 'Piano'){
                jQuery("#instrumentyesno2").css("display", "block");
            }
            else{
                jQuery("#instrumentyesno2").css("display", "none");
            }
        });

		jQuery( "#step3_submit" ).click(function() {
			var prearranged_groupall =   jQuery( "#prearranged_groupall" ).val();
			
			if(prearranged_groupall == 1 || prearranged_groupall == 2){
				var pemail1 = jQuery("#contactpersonemail1").val();
				var pemail2 = jQuery("#contactpersonemail2").val();
				var pattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
				if(pemail1 != ''){
					if(pattern.test(pemail1)==""){
		            	alert("Add Valid Email Id");
		            	return false;
		        	}
				}
				if(pemail2 != ''){
					if(pattern.test(pemail2)==""){
		            	alert("Add Valid Email Id");
		            	return false;
		        	}
				}
			}
		});
		
		jQuery( "#nextensemble" ).click(function(event) {
			var chamber_assemble =   jQuery( "#chamber_assemble" ).val();
			var prearranged_groupall =   jQuery( "#prearranged_groupall" ).val();
			if(chamber_assemble == 1){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment2").prop('required',false);

				jQuery(".table3").css("display", "block");
				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "none");	
				jQuery(".assignedchamber2").css("display", "none");
			}
			else if(chamber_assemble == 2){
				jQuery(".parentheader").css("display", "block");
				jQuery(".chamber_assembleappend1").css("display", "block");

				// Validation
				// jQuery("#instrument1").prop('required',true);
				// jQuery("#comment1").prop('required',true);

				// jQuery("#instrument2").prop('required',true);
				// jQuery("#comment2").prop('required',true);

				jQuery(".assignedchamber1").css("display", "block");
				jQuery(".table3").css("display", "block");
				jQuery(".chamber_assembleappend2").css("display", "block");
				jQuery(".assignedchamber2").css("display", "block");
			}
			else{
				validator.resetForm();
				jQuery(".parentheader").css("display", "none");
				jQuery(".chamber_assembleappend1").css("display", "none");

				// Validation
				// jQuery("#instrument1").prop('required',false);
				// jQuery("#comment1").prop('required',false);
				
				// jQuery("#instrument2").prop('required',false);
				// jQuery("#comment3").prop('required',false);

				jQuery(".assignedchamber1").css("display", "none");
				jQuery(".table3").css("display", "none");
				jQuery(".chamber_assembleappend2").css("display", "none");
				jQuery(".assignedchamber2").css("display", "none");
			}

			if(prearranged_groupall == 1){
				jQuery(".prearranged_groupall1").css("display", "block");

				jQuery(".prearranged_groupall2").css("display", "none");

				// validation
				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);

				

				// jQuery("#p_comment1").prop('required',true);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);

			}
			else if(prearranged_groupall == 2){
				jQuery(".prearranged_groupall1").css("display", "block");
				jQuery(".prearranged_groupall2").css("display", "block");

				// jQuery("#p_instrument1").prop('required',true);
				// jQuery("#contactpersonname1").prop('required',true);
				// jQuery("#contactpersonemail1").prop('required',true);
				
				// jQuery("#p_comment1").prop('required',true);
				// jQuery("#p_instrument2").prop('required',true);
				// jQuery("#contactpersonname2").prop('required',true);
				// jQuery("#contactpersonemail2").prop('required',true);
				

				// jQuery("#p_comment2").prop('required',true);
			}
			else{
				validator.resetForm();    
				jQuery(".prearranged_groupall1").css("display", "none");
				jQuery(".prearranged_groupall2").css("display", "none");

				// jQuery("#p_instrument1").prop('required',false);
				// jQuery("#contactpersonname1").prop('required',false);
				// jQuery("#contactpersonemail1").prop('required',false);
				// jQuery("#p_comment1").prop('required',false);

				// jQuery("#p_instrument2").prop('required',false);
				// jQuery("#contactpersonname2").prop('required',false);
				// jQuery("#contactpersonemail2").prop('required',false);
				// jQuery("#p_comment2").prop('required',false);
			}
		});
		
		jQuery( "#p_instrumentno1" ).click(function(event) {
			jQuery("#prearrangedyes1").css("display", "none");
			jQuery("#prearrangedno1").css("display", "block");

			// Validation
			// jQuery("#contactpersonname1").prop('required',true);
			// jQuery("#contactpersonemail1").prop('required',true);
			
			// jQuery("#otherparticipant1").prop('required',false);

		});


		
		jQuery( "#p_instrumentyes1" ).click(function(event) {
			jQuery("#prearrangedno1").css("display", "none");
			jQuery("#prearrangedyes1").css("display", "block");

			// Validation
			// jQuery("#contactpersonname1").prop('required',false);
			// jQuery("#contactpersonemail1").prop('required',false);
			
			// jQuery("#otherparticipant1").prop('required',true);
		});
		
		jQuery( "#p_instrumentno2" ).click(function(event) {
			jQuery("#prearrangedyes2").css("display", "none");
			jQuery("#prearrangedno2").css("display", "block");
			// Validation
			// jQuery("#contactpersonname2").prop('required',true);
			// jQuery("#contactpersonemail2").prop('required',true);

			

			// jQuery("#otherparticipant2").prop('required',false);

		});

		jQuery( "#p_instrumentyes2" ).click(function(event) {

			jQuery("#prearrangedno2").css("display", "none");
			jQuery("#prearrangedyes2").css("display", "block");

			// Validation
			
			// jQuery("#contactpersonname2").prop('required',false);
			// jQuery("#contactpersonemail2").prop('required',false);

			// jQuery("#otherparticipant2").prop('required',true);

		});

		jQuery( "#ownmusicyes1" ).click(function(event) {
			jQuery(".ownmusicyes1").css("display", "block");
		});

		jQuery( "#ownmusicno1" ).click(function(event) {
			jQuery(".ownmusicyes1").css("display", "none");
		});

		jQuery( "#ownmusicyes2" ).click(function(event) {
			jQuery(".ownmusicyes2").css("display", "block");
		});

		jQuery( "#ownmusicno2" ).click(function(event) {
			jQuery(".ownmusicyes2").css("display", "none");
		});

		jQuery( ".moredata" ).click(function(event) {
			  alert("Field " + event.target.id + " changed");
		});

		jQuery( "#sbmittranscation" ).click(function() {
				var custompayval = jQuery('.traamount').val();
				
				if(custompayval == '' ){
			  		alert('Please enter amount');
			  		return false;
			  	}
			  	else if(custompayval <= 0 ){
			  		alert('Please enter valid amount');
			  		return false;
			  	}
			  	else{
			  		return true;
			  	}
		});

		jQuery( "#paynow" ).click(function() {
			if(jQuery('#custompay').is(':checked')){
				var custompayval = parseInt(jQuery('.custompay').val());
				var paybleamount = parseInt(jQuery('#paybleamount').val());
				
				if(custompayval == '' ){
			  		alert('Please enter amount');
			  		return false;
			  	}
			  	else if(custompayval <= 0 ){
			  		alert('Please enter valid amount');
			  		return false;
			  	}
			  	else if(custompayval > paybleamount){
			  		alert('Amount is greater then payable amount.');
			  		return false;
			  	}
			  	else{
			  		return true;
			  	}
			}
		});

		jQuery("#menu-item-35").css("display", "none");

		jQuery( ".removerequire" ).click(function() {
		  jQuery(".custompay").prop('required',false);
		});

		jQuery(".custom-scroll").addClass("darkHeader");
		jQuery( "#addnewpayment" ).click(function() {
		  jQuery("#cheque_form").css("display", "block");
		});

		jQuery( "#makepayment" ).click(function() {
		  jQuery(".paymentoption").css("display", "block");
		});

		jQuery( "#sendingcheck" ).click(function() {
		  jQuery(".fullpaymentoptions").css("display", "none");
		});

		jQuery( "#onlinepayment" ).click(function() {
		  jQuery(".fullpaymentoptions").css("display", "block");
		});

		jQuery( "#custompay" ).click(function() {
		  jQuery(".custompay").css("display", "block");
		});

		jQuery( ".fullpay" ).click(function() {
			jQuery(".custompay").val('');
		  jQuery(".custompay").css("display", "none");
		  jQuery(".fullamount").css("display", '');
		  jQuery(".depositeamount").css("display", "none");
		  jQuery(".customamount").css("display", "none");
		});

		jQuery( ".depositepay" ).click(function() {
			jQuery(".custompay").val('');
			jQuery(".custompay").css("display", "none");
			jQuery(".fullamount").css("display", "none");
			jQuery(".depositeamount").css("display", "");
			jQuery(".customamount").css("display", "none");
		});

		jQuery( "#custompay" ).click(function() {
		  jQuery(".custompay").prop('required',true);
		  jQuery(".fullamount").css("display", "none");
		  jQuery(".depositeamount").css("display", "none");
		  jQuery(".customamount").css("display", "");
		  jQuery(".customamount").html('0');
		  jQuery(".cuspercent").html('0');
		});

		jQuery( ".custompay" ).blur(function() {
		  	var payamount = jQuery('.custompay').val();
		  	var percentdcustom = parseInt(payamount)+parseInt(normal_single_room);
		  	var percentdcustom = (parseInt(payamount)*3)/parseInt(100);
 			
 			var cusamount = parseInt(payamount) + 0.30 + parseInt(percentdcustom);

 			var cusamount = 0.30 + percentdcustom;
 			var cusamount = cusamount + parseInt(payamount);

 			var depositepercent = cusamount - payamount;

 			
		  jQuery(".fullamount").css("display", "none");
		  jQuery(".depositeamount").css("display", "none");
		  jQuery(".customamount").html(cusamount);
		  jQuery(".cuspercent").html(parseFloat(parseFloat(depositepercent).toPrecision(2)))
		  jQuery(".customamount").css("display", "");
		});

		jQuery("#gform_1").append('<input type="hidden" name="amount" id="amount" value="">');
		
		jQuery('#example').DataTable( {
	        "order": [[ 3, "desc" ]]
	    });
	    
	    jQuery('#example-enrollment').DataTable( {
	        "order": [[ 0, "asc" ]]
	    });

		<?php
		if ( !is_user_logged_in() ) {
			?>
			jQuery('#changeprofile').hide();
			<?php
		}
		?>
		var len=jQuery("table.gv-table-view tbody tr td").length;
		if(len>1){
			jQuery(".gform_wrapper").hide();
		}

		jQuery('.profilediv').hide();
		jQuery( "#changeprofile" ).click(function() {
		  jQuery('.profilediv').show();
		});
		jQuery( "#cancelprofile" ).click(function() {
		  jQuery('.profilediv').hide();
		});
	});

	
</script>

<?php

$status = get_post_meta( 218, 'enrollment_status', true );

$user = wp_get_current_user();

global $wpdb;
$querystr = "
SELECT * 
FROM wp_rg_lead
WHERE created_by = $user->ID
AND form_id = 1
";

$check_user = $wpdb->get_results($querystr, OBJECT);

foreach ($check_user as $formdata) {
$FormID = $formdata->form_id;
}

if($FormID == ''){
}
else{
	if($status == 'open'){
	}
	else{
		$current_user = wp_get_current_user();
		if($current_user->roles[0] != 'administrator'){
		?>
		<script type="text/javascript">
		// Script to hide form on submit first entry inside event table
		jQuery(document).ready(function(){
			jQuery('#gv-field-3-edit_link').hide();
			jQuery('.gv-button-update').hide();
		});
		</script>
		<?php
	}
	}
}
?>
</body>
</html>