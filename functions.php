<?php
/**
 * Fiftyshadesfurniture engine room
 *
 * @package fiftyshadesfurniture
 */

/**
 * Assign the Fiftyshadesfurniture version to a var
 */
$theme              = wp_get_theme( 'fiftyshadesfurniture' );
$fiftyshadesfurniture_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$fiftyshadesfurniture = (object) array(
	'version'    => $fiftyshadesfurniture_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-fiftyshadesfurniture.php',
	'customizer' => require 'inc/customizer/class-fiftyshadesfurniture-customizer.php',
);

require 'inc/fiftyshadesfurniture-functions.php';
require 'inc/fiftyshadesfurniture-template-hooks.php';
require 'inc/fiftyshadesfurniture-template-functions.php';

if ( fiftyshadesfurniture_is_woocommerce_activated() ) {
	$fiftyshadesfurniture->woocommerce            = require 'inc/woocommerce/class-fiftyshadesfurniture-woocommerce.php';
	$fiftyshadesfurniture->woocommerce_customizer = require 'inc/woocommerce/class-fiftyshadesfurniture-woocommerce-customizer.php';

	require 'inc/woocommerce/class-fiftyshadesfurniture-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/fiftyshadesfurniture-woocommerce-template-hooks.php';
	require 'inc/woocommerce/fiftyshadesfurniture-woocommerce-template-functions.php';
	require 'inc/woocommerce/fiftyshadesfurniture-woocommerce-functions.php';
}

if ( is_admin() ) {
	$fiftyshadesfurniture->admin = require 'inc/admin/class-fiftyshadesfurniture-admin.php';

}

/**
 * NUX
 */
if (is_admin() || is_customize_preview() ) {
	require 'inc/nux/class-fiftyshadesfurniture-nux-admin.php';
	require 'inc/nux/class-fiftyshadesfurniture-nux-guided-tour.php';
	require 'inc/nux/class-fiftyshadesfurniture-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

 /**
 * Remove the breadcrumbs
 */
add_action( 'init', 'bc_remove_wc_breadcrumbs' );
function bc_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

 /**
 * Remove the WordPress logo...
 */
add_action( 'wp_before_admin_bar_render', 'tweaked_admin_bar' );
function tweaked_admin_bar() {
	global $wp_admin_bar;

	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu( 'comments' );
}

add_action( 'admin_menu', 'wpdocs_remove_menus' );
function wpdocs_remove_menus() {
	 remove_menu_page( 'edit.php' );
	 remove_menu_page( 'edit-comments.php' );
}
