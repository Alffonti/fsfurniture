<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package fiftyshadesfurniture
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<header class="site-header" role="banner">

		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'fiftyshadesfurniture' ); ?>">
			<button id="site-navigation-menu-toggle" class="menu-toggle" aria-controls="site-navigation" aria-expanded="false">
				<span></span>
			</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'primary',
						'container_class' => 'primary-navigation',
					)
				);

				wp_nav_menu(
					array(
						'theme_location'  => 'handheld',
						'container_class' => 'handheld-navigation',
					)
				);
				?>
		</nav><!-- #site-navigation -->

		<div class="site-branding">
			<?php fiftyshadesfurniture_site_title_or_logo(); ?>
		</div>

		<?php if ( fiftyshadesfurniture_is_woocommerce_activated() ) : ?>
			<div class="site-search">
				<?php the_widget( 'WC_Widget_Product_Search', 'title=' ); ?>
			</div>
		<?php endif; ?>

		<div class="fiftyshadesfurniture-primary-navigation">
			<ul id="site-header-cart" class="site-header-cart menu">
				<li class="current-menu-item">
					<?php fiftyshadesfurniture_cart_link(); ?>
				</li>
				<li>
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				</li>
			</ul>
		</div>
	</header><!-- .site-header -->

	<?php
	/**
	 * Functions hooked in to fiftyshadesfurniture_before_content
	 *
	 * @hooked fiftyshadesfurniture_header_widget_region - 10
	 * @hooked woocommerce_breadcrumb - 10
	 */
	do_action( 'fiftyshadesfurniture_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		do_action( 'fiftyshadesfurniture_content_top' );
