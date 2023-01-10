<?php

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area" role="complementary">
	<h1>Hola sidebar</h1>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
