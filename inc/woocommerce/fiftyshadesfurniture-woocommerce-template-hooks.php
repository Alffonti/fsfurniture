<?php
/**
 * Fiftyshadesfurniture WooCommerce hooks
 *
 * @package fiftyshadesfurniture
 */

/**
 * Homepage
 *
 * @see  fiftyshadesfurniture_product_categories()
 * @see  fiftyshadesfurniture_recent_products()
 * @see  fiftyshadesfurniture_featured_products()
 * @see  fiftyshadesfurniture_popular_products()
 * @see  fiftyshadesfurniture_on_sale_products()
 * @see  fiftyshadesfurniture_best_selling_products()
 */
add_action( 'homepage', 'fiftyshadesfurniture_product_categories', 20 );
add_action( 'homepage', 'fiftyshadesfurniture_recent_products', 30 );
add_action( 'homepage', 'fiftyshadesfurniture_featured_products', 40 );
add_action( 'homepage', 'fiftyshadesfurniture_popular_products', 50 );
add_action( 'homepage', 'fiftyshadesfurniture_on_sale_products', 60 );
add_action( 'homepage', 'fiftyshadesfurniture_best_selling_products', 70 );

/**
 * Layout
 *
 * @see  fiftyshadesfurniture_before_content()
 * @see  fiftyshadesfurniture_after_content()
 * @see  fiftyshadesfurniture_shop_messages()
 */

 remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_main_content', 'fiftyshadesfurniture_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'fiftyshadesfurniture_after_content', 10 );
add_action( 'fiftyshadesfurniture_content_top', 'fiftyshadesfurniture_shop_messages', 15 );

add_action( 'woocommerce_after_shop_loop', 'fiftyshadesfurniture_sorting_wrapper', 9 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 );
add_action( 'woocommerce_after_shop_loop', 'fiftyshadesfurniture_sorting_wrapper_close', 31 );

add_action( 'woocommerce_before_shop_loop', 'fiftyshadesfurniture_sorting_wrapper', 9 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
add_action( 'woocommerce_before_shop_loop', 'fiftyshadesfurniture_woocommerce_pagination', 30 );
add_action( 'woocommerce_before_shop_loop', 'fiftyshadesfurniture_sorting_wrapper_close', 31 );

add_action( 'fiftyshadesfurniture_footer', 'fiftyshadesfurniture_handheld_footer_bar', 999 );

/**
 * Products
 *
 * @see fiftyshadesfurniture_edit_post_link()
 * @see fiftyshadesfurniture_upsell_display()
 * @see fiftyshadesfurniture_single_product_pagination()
 * @see fiftyshadesfurniture_sticky_single_add_to_cart()
 */
add_action( 'woocommerce_single_product_summary', 'fiftyshadesfurniture_edit_post_link', 60 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'fiftyshadesfurniture_upsell_display', 15 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );

add_action( 'woocommerce_after_single_product_summary', 'fiftyshadesfurniture_single_product_pagination', 30 );
add_action( 'fiftyshadesfurniture_after_footer', 'fiftyshadesfurniture_sticky_single_add_to_cart', 999 );

/**
 * Header
 *
 * @see fiftyshadesfurniture_product_search()
 */
add_action( 'fiftyshadesfurniture_header', 'fiftyshadesfurniture_product_search', 40 );

/**
 * Cart fragment
 *
 * @see fiftyshadesfurniture_cart_link_fragment()
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'fiftyshadesfurniture_cart_link_fragment' );

/**
 * Integrations
 *
 * @see fiftyshadesfurniture_woocommerce_brands_archive()
 * @see fiftyshadesfurniture_woocommerce_brands_single()
 * @see fiftyshadesfurniture_woocommerce_brands_homepage_section()
 */
if ( class_exists( 'WC_Brands' ) ) {
	add_action( 'woocommerce_archive_description', 'fiftyshadesfurniture_woocommerce_brands_archive', 5 );
	add_action( 'woocommerce_single_product_summary', 'fiftyshadesfurniture_woocommerce_brands_single', 4 );
	add_action( 'homepage', 'fiftyshadesfurniture_woocommerce_brands_homepage_section', 80 );
}
