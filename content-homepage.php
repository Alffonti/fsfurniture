<?php
/**
 * The template used for displaying page content in template-homepage.php
 *
 * @package fiftyshadesfurniture
 */

?>
<?php
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>

<h1>Hola World</h1>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="<?php fiftyshadesfurniture_homepage_content_styles(); ?>" data-featured-image="<?php echo esc_url( $featured_image ); ?>">
	<div class="col-full">

		<?php
		/**
		 * Functions hooked in to fiftyshadesfurniture_page add_action
		 *
		 * @hooked fiftyshadesfurniture_homepage_header      - 10
		 * @hooked fiftyshadesfurniture_page_content         - 20
		 */
		do_action( 'fiftyshadesfurniture_homepage' );
		?>
	</div>
</div><!-- #post-## -->
