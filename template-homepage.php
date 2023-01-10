<?php
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			/**
			 * Functions hooked in to homepage action
			 *
			 * @hooked fiftyshadesfurniture_homepage_content      - 10
			 * @hooked fiftyshadesfurniture_product_categories    - 20
			 * @hooked fiftyshadesfurniture_recent_products       - 30
			 * @hooked fiftyshadesfurniture_featured_products     - 40
			 * @hooked fiftyshadesfurniture_popular_products      - 50
			 * @hooked fiftyshadesfurniture_on_sale_products      - 60
			 * @hooked fiftyshadesfurniture_best_selling_products - 70
			 */
			do_action( 'homepage' );
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_footer();
