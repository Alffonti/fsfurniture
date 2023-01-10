<?php
/**
 * Fiftyshadesfurniture hooks
 *
 * @package fiftyshadesfurniture
 */

/**
 * General
 *
 * @see  fiftyshadesfurniture_header_widget_region()
 * @see  fiftyshadesfurniture_get_sidebar()
 */
add_action( 'fiftyshadesfurniture_before_content', 'fiftyshadesfurniture_header_widget_region', 10 );
add_action( 'fiftyshadesfurniture_sidebar', 'fiftyshadesfurniture_get_sidebar', 10 );

/**
 * Footer
 *
 * @see  fiftyshadesfurniture_footer_widgets()
 * @see  fiftyshadesfurniture_credit()
 */
add_action( 'fiftyshadesfurniture_footer', 'fiftyshadesfurniture_footer_widgets', 10 );
add_action( 'fiftyshadesfurniture_footer', 'fiftyshadesfurniture_credit', 20 );

/**
 * Homepage
 *
 * @see  fiftyshadesfurniture_homepage_content()
 */
add_action( 'homepage', 'fiftyshadesfurniture_homepage_content', 10 );

/**
 * Posts
 *
 * @see  fiftyshadesfurniture_post_header()
 * @see  fiftyshadesfurniture_post_meta()
 * @see  fiftyshadesfurniture_post_content()
 * @see  fiftyshadesfurniture_paging_nav()
 * @see  fiftyshadesfurniture_single_post_header()
 * @see  fiftyshadesfurniture_post_nav()
 * @see  fiftyshadesfurniture_display_comments()
 */
add_action( 'fiftyshadesfurniture_loop_post', 'fiftyshadesfurniture_post_header', 10 );
add_action( 'fiftyshadesfurniture_loop_post', 'fiftyshadesfurniture_post_content', 30 );
add_action( 'fiftyshadesfurniture_loop_post', 'fiftyshadesfurniture_post_taxonomy', 40 );
add_action( 'fiftyshadesfurniture_loop_after', 'fiftyshadesfurniture_paging_nav', 10 );
add_action( 'fiftyshadesfurniture_single_post', 'fiftyshadesfurniture_post_header', 10 );
add_action( 'fiftyshadesfurniture_single_post', 'fiftyshadesfurniture_post_content', 30 );
add_action( 'fiftyshadesfurniture_single_post_bottom', 'fiftyshadesfurniture_edit_post_link', 5 );
add_action( 'fiftyshadesfurniture_single_post_bottom', 'fiftyshadesfurniture_post_taxonomy', 5 );
add_action( 'fiftyshadesfurniture_single_post_bottom', 'fiftyshadesfurniture_post_nav', 10 );
add_action( 'fiftyshadesfurniture_single_post_bottom', 'fiftyshadesfurniture_display_comments', 20 );
add_action( 'fiftyshadesfurniture_post_header_before', 'fiftyshadesfurniture_post_meta', 10 );
add_action( 'fiftyshadesfurniture_post_content_before', 'fiftyshadesfurniture_post_thumbnail', 10 );

/**
 * Pages
 *
 * @see  fiftyshadesfurniture_page_header()
 * @see  fiftyshadesfurniture_page_content()
 * @see  fiftyshadesfurniture_display_comments()
 */
add_action( 'fiftyshadesfurniture_page', 'fiftyshadesfurniture_page_header', 10 );
add_action( 'fiftyshadesfurniture_page', 'fiftyshadesfurniture_page_content', 20 );
add_action( 'fiftyshadesfurniture_page', 'fiftyshadesfurniture_edit_post_link', 30 );
add_action( 'fiftyshadesfurniture_page_after', 'fiftyshadesfurniture_display_comments', 10 );

/**
 * Homepage Page Template
 *
 * @see  fiftyshadesfurniture_homepage_header()
 * @see  fiftyshadesfurniture_page_content()
 */
add_action( 'fiftyshadesfurniture_homepage', 'fiftyshadesfurniture_homepage_header', 10 );
add_action( 'fiftyshadesfurniture_homepage', 'fiftyshadesfurniture_page_content', 20 );
