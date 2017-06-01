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
	jQuery(document).ready(function(){

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
<?php
$current_user = wp_get_current_user();

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

<script type="text/javascript">

	<?php
	// If user role : subscriber
	if($current_user->roles[0] == 'subscriber' || $current_user->roles[0] == 'administrator'){
	?>
		setInterval(function(){
			var normal_reg = jQuery('#normal_reg').val();
			var normal_instrument = jQuery('#normal_instrument').val();
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var normal_tshirt = jQuery('#normal_tshirt').val();

			var totalfee = 0;
			totalfee = parseInt(totalfee)+parseInt(normal_reg);

			// check instrument
			if(jQuery("#choice_1_54_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_instrument);
			}

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(normal_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			jQuery('.custotal-amount').html(totalfee);    jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : subscriber

	// If user role : Faculty and Staff
	if($current_user->roles[0] == 'faculty' || $current_user->roles[0] == 'staff'){
	?>
		setInterval(function(){
			var faculty_staff_single_room = jQuery('#faculty_staff_single_room').val();
			var faculty_staff_wine = jQuery('#faculty_staff_wine').val();
			var faculty_staff_tshirt = jQuery('#faculty_staff_tshirt').val();

			var totalfee = 0;
			
			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(faculty_staff_single_room);
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			var winetotal = parseInt(faculty_staff_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(faculty_staff_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			jQuery('.custotal-amount').html(totalfee);    jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : Faculty and Staff

	// If user role : Board
	if($current_user->roles[0] == 'board'){
	?>
		setInterval(function(){

			var board_registration_and_instrumental = jQuery('#board_registration_and_instrumental').val();
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var board_user_per_tshirt = jQuery('#board_user_per_tshirt').val();

			var totalfee = 0;

			// check instrument
			if(jQuery("#choice_1_54_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(board_registration_and_instrumental);
			}

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(board_user_per_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			jQuery('.custotal-amount').html(totalfee);    jQuery('#amount').val(totalfee);

		}, 500);
	<?php
	}
	// End If user role : Board

	// If user role : Volunteer
	if($current_user->roles[0] == 'volunteer'){
	?>
		setInterval(function(){
			var normal_double_room = jQuery('#normal_double_room').val();
			var normal_single_room = jQuery('#normal_single_room').val();
			var normal_all_meal = jQuery('#normal_all_meal').val();
			var normal_lunch_dinner = jQuery('#normal_lunch_dinner').val();
			var normal_wine = jQuery('#normal_wine').val();
			var normal_tshirt = jQuery('#normal_tshirt').val();

			var totalfee = 0;

			// check Double dorm room
			if(jQuery("#choice_1_55_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_double_room);
			}

			// check Single dorm room
			if(jQuery("#choice_1_55_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_single_room);
			}

			// check All Meals
			if(jQuery("#choice_1_56_0").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_all_meal);
			}

			// check Lunch and Dinner Only
			if(jQuery("#choice_1_56_1").is(":checked")) {
			  	totalfee = parseInt(totalfee)+parseInt(normal_lunch_dinner);
			}

			// check Wine Glass
			var wineglass = jQuery("#input_1_36 ").val();
			var winetotal = parseInt(normal_wine*wineglass);
			totalfee = parseInt(totalfee)+parseInt(winetotal);

			// check TShirt
			var small = parseInt(jQuery("#input_1_38 ").val());
			var medium = parseInt(jQuery("#input_1_39 ").val());
			var large = parseInt(jQuery("#input_1_40 ").val());
			var xl = parseInt(jQuery("#input_1_41 ").val());
			var xxl = parseInt(jQuery("#input_1_42 ").val());
			var xxxl = parseInt(jQuery("#input_1_43 ").val());
			var totalshirt = small+medium+large+xl+xxl+xxxl;
			var shirttotal = parseInt(normal_tshirt*totalshirt);
			totalfee = parseInt(totalfee)+parseInt(shirttotal);
			jQuery('.custotal-amount').html(totalfee);    jQuery('#amount').val(totalfee);

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
			jQuery('.deleteid').val(delID);
			jQuery('#deleteform').submit();
		}
	
	jQuery(document).ready(function(){

		jQuery( "#makepayment" ).click(function() {
		  jQuery(".paymentoption").css("display", "block");
		});

		jQuery( "#sendingcheck" ).click(function() {
		  jQuery(".fullpaymentoptions").css("display", "none");
		});

		jQuery( "#onlinepayment" ).click(function() {
		  jQuery(".fullpaymentoptions").css("display", "block");
		});

		jQuery("#gform_1").append('<input type="text" name="amount" id="amount" value="">');
		
		jQuery('#example').DataTable( {
	        "order": [[ 3, "desc" ]]
	    } );

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