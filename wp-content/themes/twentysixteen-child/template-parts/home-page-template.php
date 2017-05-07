<?php
/**
 * Template Name: Home Page
 *
 * @subpackage Twenty_Sixteen child theme
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */


get_header();
$announcement_title = get_field( "announcement_title" );
$announcement_content = get_field( "announcement_content" );

?>




<div id="primary" class="content-area-home">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			//get_template_part( 'template-parts/content', 'page' );
            echo get_the_content();
            echo '<br/><br/>';
           
            echo '<h3 class="ann_title">'.$announcement_title.'</h3>'; 
            echo '<p class="ann_content">'.$announcement_content.'</p>';
            if(!is_user_logged_in()){
            ?>
            <div class="login_register">
                <a href="<?php echo site_url('/login'); ?>">Login</a>
                <a href="<?php echo site_url('/register'); ?>">Register</a>
            </div>
		<?php
            }
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php //get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->





<?php //get_sidebar(); ?>
<?php get_footer(); ?>
