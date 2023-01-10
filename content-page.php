<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package fiftyshadesfurniture
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to fiftyshadesfurniture_page add_action
	 *
	 * @hooked fiftyshadesfurniture_page_header          - 10
	 * @hooked fiftyshadesfurniture_page_content         - 20
	 */
	do_action( 'fiftyshadesfurniture_page' );
	?>
</article><!-- #post-## -->
