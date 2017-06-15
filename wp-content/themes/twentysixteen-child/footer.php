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

<input type="hidden" name="grevityuserrole" id="grevityuserrole" value="">

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

			jQuery('.custotal-amount').html(regfee + instrumentdata + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);    

			jQuery('#amount').val(totalfee);

			}, 500);
		
		}

		if(grevityuserrole == 'faculty' || grevityuserrole == 'staff'){

			var faculty_staff_single_room = jQuery('#faculty_staff_single_room').val();
			var faculty_staff_wine = jQuery('#faculty_staff_wine').val();
			var faculty_staff_tshirt = jQuery('#faculty_staff_tshirt').val();

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

				jQuery('.custotal-amount').html( single_room_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);

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

					

					jQuery('.custotal-amount').html(regfee + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);

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

					jQuery('.custotal-amount').html(double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);
	  
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

			jQuery('.custotal-amount').html("<table class='custom-enroll'>" + regfee + instrumentdata + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<tr><td> Total : $" +  totalfee) + "</td></tr></table>";    

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

			jQuery('.custotal-amount').html( single_room_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);

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

			jQuery('.custotal-amount').html(regfee + double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);

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

				jQuery('.custotal-amount').html(double_room_data + single_room_data + full_meal_data + lunch_dinner_data + wine_data + tshirt_data + donationdata + "<br> Total : $" +  totalfee);
  
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