<?php
/**
 * Template used to display post content.
 *
 * @package fiftyshadesfurniture
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * Functions hooked in to fiftyshadesfurniture_loop_post action.
	 *
	 * @hooked fiftyshadesfurniture_post_header          - 10
	 * @hooked fiftyshadesfurniture_post_content         - 30
	 * @hooked fiftyshadesfurniture_post_taxonomy        - 40
	 */
	do_action( 'fiftyshadesfurniture_loop_post' );
	?>

</article><!-- #post-## -->
