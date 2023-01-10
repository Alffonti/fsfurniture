<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full width
 *
 * @package fiftyshadesfurniture
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) :
				the_post();

				do_action( 'fiftyshadesfurniture_page_before' );

				get_template_part( 'content', 'page' );

				/**
				 * Functions hooked in to fiftyshadesfurnitur_page_after action
				 *
				 * @hooked fiftyshadesfurnitur_display_comments - 10
				 */
				do_action( 'fiftyshadesfurniture_page_after' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
