<?php
/**
 * Fiftyshadesfurniture Admin Class
 *
 * @package  fiftyshadesfurniture
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Fiftyshadesfurniture_Admin' ) ) :
	/**
	 * The Fiftyshadesfurniture admin class
	 */
	class Fiftyshadesfurniture_Admin {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'welcome_register_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'welcome_style' ) );
			add_action( 'wp_before_admin_bar_render', array( $this, 'tweaked_admin_bar' ) );
		}

				/**
		 * Load welcome screen css
		 */
		public function tweaked_admin_bar() {
			global $wp_admin_bar;

			//Remove the WordPress logo...
			$wp_admin_bar->remove_menu('wp-logo');
		}

		/**
		 * Load welcome screen css
		 *
		 * @param string $hook_suffix the current page hook suffix.
		 * @return void
		 * @since  1.4.4
		 */
		public function welcome_style( $hook_suffix ) {
			global $fiftyshadesfurniture_version;

			if ( 'appearance_page_fiftyshadesfurniture-welcome' === $hook_suffix ) {
				wp_enqueue_style( 'fiftyshadesfurniture-welcome-screen', get_template_directory_uri() . '/assets/css/admin/welcome-screen/welcome.css', array(), $fiftyshadesfurniture_version );
			}
		}

		/**
		 * Creates the dashboard page
		 *
		 * @see  add_theme_page()
		 * @since 1.0.0
		 */
		public function welcome_register_menu() {
			add_theme_page( 'Fiftyshadesfurniture', 'Fiftyshadesfurniture', 'activate_plugins', 'fiftyshadesfurniture-welcome', array( $this, 'fiftyshadesfurniture_welcome_screen' ) );
		}

		/**
		 * The welcome screen
		 *
		 * @since 1.0.0
		 */
		public function fiftyshadesfurniture_welcome_screen() {
			require_once ABSPATH . 'wp-load.php';
			require_once ABSPATH . 'wp-admin/admin.php';
			require_once ABSPATH . 'wp-admin/admin-header.php';

			global $fiftyshadesfurniture_version;

			$show_setup_screen = ( false === (bool) get_option( 'fiftyshadesfurniture_nux_dismissed' ) ) && ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '4.8.0', '>=' ) );
			?>

			<div class="fiftyshadesfurniture-wrap">
				<section class="fiftyshadesfurniture-welcome-nav">
					<span class="fiftyshadesfurniture-welcome-nav__version">Fiftyshadesfurniture <?php echo esc_attr( $fiftyshadesfurniture_version ); ?></span>
					<ul>
						<li><a href="https://wordpress.org/support/theme/fiftyshadesfurniture" target="_blank"><?php esc_html_e( 'Support', 'fiftyshadesfurniture' ); ?></a></li>
						<li><a href="https://docs.woocommerce.com/documentation/themes/fiftyshadesfurniture/" target="_blank"><?php esc_html_e( 'Documentation', 'fiftyshadesfurniture' ); ?></a></li>
						<li><a href="https://developer.woocommerce.com/category/release-post/fiftyshadesfurniture-theme-release-notes/" target="_blank"><?php esc_html_e( 'Development blog', 'fiftyshadesfurniture' ); ?></a></li>
					</ul>
				</section>

				<div class="fiftyshadesfurniture-logo">
					<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/admin/fiftyshadesfurniture-icon.svg" alt="Fiftyshadesfurniture" />
				</div>

				<div class="fiftyshadesfurniture-intro">
					<?php
					if ( $show_setup_screen ) {
						?>
						<div class="fiftyshadesfurniture-intro-setup">
							<?php
							Fiftyshadesfurniture_NUX_Admin::admin_notices_content();
							?>
						</div>
						<?php
						echo '<div class="fiftyshadesfurniture-intro-message" style="display:none">';
					}

					/**
					 * Display a different message when the user visits this page when returning from the guided tour
					 */
					$referrer = wp_get_referer();

					if ( strpos( $referrer, 'sf_starter_content' ) !== false ) {
						/* translators: 1: HTML, 2: HTML */
						echo '<h1>' . sprintf( esc_attr__( 'Setup complete %1$sYour Fiftyshadesfurniture adventure begins now 🚀%2$s ', 'fiftyshadesfurniture' ), '<span>', '</span>' ) . '</h1>';
						echo '<p>' . esc_attr__( 'One more thing... You might be interested in the following Fiftyshadesfurniture extensions and designs.', 'fiftyshadesfurniture' ) . '</p>';
					} else {
						echo '<p>' . esc_attr__( 'Hello! You might be interested in the following Fiftyshadesfurniture extensions and designs.', 'fiftyshadesfurniture' ) . '</p>';
					}

					if ( $show_setup_screen ) {
						echo '</div>';
					}
					?>
				</div>

				<div class="fiftyshadesfurniture-enhance">
					<div class="fiftyshadesfurniture-enhance__column fiftyshadesfurniture-bundle">
						<h3><?php esc_html_e( 'Fiftyshadesfurniture Extensions Bundle', 'fiftyshadesfurniture' ); ?></h3>
						<span class="bundle-image">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/admin/welcome-screen/fiftyshadesfurniture-bundle-hero.png" alt="Fiftyshadesfurniture Extensions Hero" />
						</span>

						<p>
							<?php esc_html_e( 'All the tools you\'ll need to define your style and customize Fiftyshadesfurniture.', 'fiftyshadesfurniture' ); ?>
						</p>

						<p>
							<?php esc_html_e( 'Make it yours without touching code with the Fiftyshadesfurniture Extensions bundle. Express yourself, optimize conversions, delight customers.', 'fiftyshadesfurniture' ); ?>
						</p>


						<p>
							<a href="https://woocommerce.com/products/fiftyshadesfurniture-extensions-bundle/?utm_source=fiftyshadesfurniture&utm_medium=product&utm_campaign=fiftyshadesfurnitureaddons" class="fiftyshadesfurniture-button" target="_blank"><?php esc_html_e( 'Read more and purchase', 'fiftyshadesfurniture' ); ?></a>
						</p>
					</div>
					<div class="fiftyshadesfurniture-enhance__column fiftyshadesfurniture-child-themes">
						<h3><?php esc_html_e( 'Alternate designs', 'fiftyshadesfurniture' ); ?></h3>
						<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/admin/welcome-screen/child-themes.jpg" alt="Fiftyshadesfurniture Powerpack" />

						<p>
							<?php esc_html_e( 'Quickly and easily transform your shops appearance with Fiftyshadesfurniture child themes.', 'fiftyshadesfurniture' ); ?>
						</p>

						<p>
							<?php esc_html_e( 'Each has been designed to serve a different industry - from fashion to food.', 'fiftyshadesfurniture' ); ?>
						</p>

						<p>
							<?php esc_html_e( 'Of course they are all fully compatible with each Fiftyshadesfurniture extension.', 'fiftyshadesfurniture' ); ?>
						</p>

						<p>
							<a href="https://woocommerce.com/product-category/themes/fiftyshadesfurniture-child-theme-themes/?utm_source=fiftyshadesfurniture&utm_medium=product&utm_campaign=fiftyshadesfurnitureaddons" class="fiftyshadesfurniture-button" target="_blank"><?php esc_html_e( 'Check \'em out', 'fiftyshadesfurniture' ); ?></a>
						</p>
					</div>
				</div>

				<div class="automattic">
					<p>
					<?php
						/* translators: %s: Automattic branding */
						printf( esc_html__( 'An %s project', 'fiftyshadesfurniture' ), '<a href="https://automattic.com/"><img src="' . esc_url( get_template_directory_uri() ) . '/assets/images/admin/welcome-screen/automattic.png" alt="Automattic" /></a>' );
					?>
					</p>
				</div>
			</div>
			<?php
		}

		/**
		 * Welcome screen intro
		 *
		 * @since 1.0.0
		 */
		public function welcome_intro() {
			require_once get_template_directory() . '/inc/admin/welcome-screen/component-intro.php';
		}

		/**
		 * Output a button that will install or activate a plugin if it doesn't exist, or display a disabled button if the
		 * plugin is already activated.
		 *
		 * @param string $plugin_slug The plugin slug.
		 * @param string $plugin_file The plugin file.
		 */
		public function install_plugin_button( $plugin_slug, $plugin_file ) {
			if ( current_user_can( 'install_plugins' ) && current_user_can( 'activate_plugins' ) ) {
				if ( is_plugin_active( $plugin_slug . '/' . $plugin_file ) ) {
					// The plugin is already active.
					$button = array(
						'message' => esc_attr__( 'Activated', 'fiftyshadesfurniture' ),
						'url'     => '#',
						'classes' => 'disabled',
					);
				} elseif ( $this->is_plugin_installed( $plugin_slug ) ) {
					$url = $this->is_plugin_installed( $plugin_slug );

					// The plugin exists but isn't activated yet.
					$button = array(
						'message' => esc_attr__( 'Activate', 'fiftyshadesfurniture' ),
						'url'     => $url,
						'classes' => 'activate-now',
					);
				} else {
					// The plugin doesn't exist.
					$url    = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $plugin_slug,
							),
							self_admin_url( 'update.php' )
						),
						'install-plugin_' . $plugin_slug
					);
					$button = array(
						'message' => esc_attr__( 'Install now', 'fiftyshadesfurniture' ),
						'url'     => $url,
						'classes' => ' install-now install-' . $plugin_slug,
					);
				}
				?>
				<a href="<?php echo esc_url( $button['url'] ); ?>" class="fiftyshadesfurniture-button <?php echo esc_attr( $button['classes'] ); ?>" data-originaltext="<?php echo esc_attr( $button['message'] ); ?>" data-slug="<?php echo esc_attr( $plugin_slug ); ?>" aria-label="<?php echo esc_attr( $button['message'] ); ?>"><?php echo esc_html( $button['message'] ); ?></a>
				<a href="https://wordpress.org/plugins/<?php echo esc_attr( $plugin_slug ); ?>" target="_blank"><?php esc_html_e( 'Learn more', 'fiftyshadesfurniture' ); ?></a>
				<?php
			}
		}

		/**
		 * Check if a plugin is installed and return the url to activate it if so.
		 *
		 * @param string $plugin_slug The plugin slug.
		 */
		private function is_plugin_installed( $plugin_slug ) {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
				$plugins = get_plugins( '/' . $plugin_slug );
				if ( ! empty( $plugins ) ) {
					$keys        = array_keys( $plugins );
					$plugin_file = $plugin_slug . '/' . $keys[0];
					$url         = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'activate',
								'plugin' => $plugin_file,
							),
							admin_url( 'plugins.php' )
						),
						'activate-plugin_' . $plugin_file
					);
					return $url;
				}
			}
			return false;
		}
		/**
		 * Welcome screen enhance section
		 *
		 * @since 1.5.2
		 */
		public function welcome_enhance() {
			require_once get_template_directory() . '/inc/admin/welcome-screen/component-enhance.php';
		}

		/**
		 * Welcome screen contribute section
		 *
		 * @since 1.5.2
		 */
		public function welcome_contribute() {
			require_once get_template_directory() . '/inc/admin/welcome-screen/component-contribute.php';
		}

		/**
		 * Get product data from json
		 *
		 * @param  string $url       URL to the json file.
		 * @param  string $transient Name the transient.
		 * @return [type]            [description]
		 */
		public function get_fiftyshadesfurniture_product_data( $url, $transient ) {
			$raw_products = wp_safe_remote_get( $url );
			$products     = json_decode( wp_remote_retrieve_body( $raw_products ) );

			if ( ! empty( $products ) ) {
				set_transient( $transient, $products, DAY_IN_SECONDS );
			}

			return $products;
		}
	}

endif;

return new Fiftyshadesfurniture_Admin();
