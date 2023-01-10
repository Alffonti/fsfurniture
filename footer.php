<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package fiftyshadesfurniture
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'fiftyshadesfurniture_before_footer' ); ?>

	<footer class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			do_action( 'fiftyshadesfurniture_footer' );
			?>

		</div><!-- .col-full -->
	</footer><!-- .site-footer -->

	<?php do_action( 'fiftyshadesfurniture_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
