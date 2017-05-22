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

		jQuery(".for-admin-only").hide();
	});
</script>	<?php
}
?>
<script type="text/javascript">
	// Script to hide form on submit first entry inside event table
	jQuery(document).ready(function(){
		
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
// Link For enrollment form
/*
$start_date = get_post_meta( 218, 'start_date', true );
$end_date = get_post_meta( 218, 'end_date', true );

$paymentDate = date('Y-m-d');
$paymentDate=date('Y-m-d', strtotime($paymentDate));;
//echo $paymentDate; // echos today! 
$contractDateBegin = date('Y-m-d', strtotime($start_date));
$contractDateEnd = date('Y-m-d', strtotime($end_date));

if (($paymentDate > $contractDateBegin) && ($paymentDate < $contractDateEnd))
{
	$status = 'open';
}
else
{
	$status = 'close';
}
*/
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
		?>
		<script type="text/javascript">
		// Script to hide form on submit first entry inside event table
		jQuery(document).ready(function(){
			jQuery('#gv-field-3-edit_link').hide();
		});
		</script>
		<?php
	}
}
?>
</body>
</html>