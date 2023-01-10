<?php
/**
 * Template used to display post content on single pages.
 *
 * @package fiftyshadesfurniture
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	do_action( 'fiftyshadesfurniture_single_post_top' );

	/**
	 * Functions hooked into fiftyshadesfurniture_single_post add_action
	 *
	 * @hooked fiftyshadesfurniture_post_header          - 10
	 * @hooked fiftyshadesfurniture_post_content         - 30
	 */
	do_action( 'fiftyshadesfurniture_single_post' );

	/**
	 * Functions hooked in to fiftyshadesfurniture_single_post_bottom action
	 *
	 * @hooked fiftyshadesfurniture_post_nav         - 10
	 * @hooked fiftyshadesfurniture_display_comments - 20
	 */
	do_action( 'fiftyshadesfurniture_single_post_bottom' );
	?>

</article><!-- #post-## -->
