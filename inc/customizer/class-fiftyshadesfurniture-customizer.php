<?php
/**
 * Fiftyshadesfurniture Customizer Class
 *
 * @package  fiftyshadesfurniture
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Fiftyshadesfurniture_Customizer' ) ) :

	/**
	 * The Fiftyshadesfurniture Customizer class
	 */
	class Fiftyshadesfurniture_Customizer {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'customize_register' ), 10 );
			add_filter( 'body_class', array( $this, 'layout_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_customizer_css' ), 130 );
			add_action( 'customize_controls_print_styles', array( $this, 'customizer_custom_control_css' ) );
			add_action( 'customize_register', array( $this, 'edit_default_customizer_settings' ), 99 );
			add_action( 'enqueue_block_assets', array( $this, 'block_editor_customizer_css' ) );
			add_action( 'init', array( $this, 'default_theme_mod_values' ), 10 );
		}

		/**
		 * Returns an array of the desired default Fiftyshadesfurniture Options
		 *
		 * @return array
		 */
		public function get_fiftyshadesfurniture_default_setting_values() {
			/**
			 * Filters for the default Fiftyshadesfurniture Options.
			 *
			 * @param array $args
			 * @package     fiftyshadesfurniture
			 * @since       2.0.0
			 */
			return apply_filters(
				'fiftyshadesfurniture_setting_default_values',
				$args = array(
					'fiftyshadesfurniture_heading_color'           => '#333333',
					'fiftyshadesfurniture_text_color'              => '#6d6d6d',
					'fiftyshadesfurniture_accent_color'            => '#7f54b3',
					'fiftyshadesfurniture_hero_heading_color'      => '#000000',
					'fiftyshadesfurniture_hero_text_color'         => '#000000',
					'fiftyshadesfurniture_header_background_color' => '#ffffff',
					'fiftyshadesfurniture_header_text_color'       => '#404040',
					'fiftyshadesfurniture_header_link_color'       => '#333333',
					'fiftyshadesfurniture_footer_background_color' => '#f0f0f0',
					'fiftyshadesfurniture_footer_heading_color'    => '#333333',
					'fiftyshadesfurniture_footer_text_color'       => '#6d6d6d',
					'fiftyshadesfurniture_footer_link_color'       => '#333333',
					'fiftyshadesfurniture_button_background_color' => '#eeeeee',
					'fiftyshadesfurniture_button_text_color'       => '#333333',
					'fiftyshadesfurniture_button_alt_background_color' => '#333333',
					'fiftyshadesfurniture_button_alt_text_color'   => '#ffffff',
					'fiftyshadesfurniture_layout'                  => 'right',
					'background_color'                   => 'ffffff',
				)
			);
		}

		/**
		 * Adds a value to each Fiftyshadesfurniture setting if one isn't already present.
		 *
		 * @uses get_fiftyshadesfurniture_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( $this->get_fiftyshadesfurniture_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value Theme modification value.
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = $this->get_fiftyshadesfurniture_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter fiftyshadesfurniture_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_fiftyshadesfurniture_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( $this->get_fiftyshadesfurniture_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer along with several other settings.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 * @since  1.0.0
		 */
		public function customize_register( $wp_customize ) {

			// Move background color setting alongside background image.
			$wp_customize->get_control( 'background_color' )->section  = 'background_image';
			$wp_customize->get_control( 'background_color' )->priority = 20;

			// Change background image section title & priority.
			$wp_customize->get_section( 'background_image' )->title    = __( 'Background', 'fiftyshadesfurniture' );
			$wp_customize->get_section( 'background_image' )->priority = 30;

			// Change header image section title & priority.
			$wp_customize->get_section( 'header_image' )->title    = __( 'Header', 'fiftyshadesfurniture' );
			$wp_customize->get_section( 'header_image' )->priority = 25;

			// Selective refresh.
			if ( function_exists( 'add_partial' ) ) {
				$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
				$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

				$wp_customize->selective_refresh->add_partial(
					'custom_logo',
					array(
						'selector'        => '.site-branding',
						'render_callback' => array( $this, 'get_site_logo' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogname',
					array(
						'selector'        => '.site-title.beta a',
						'render_callback' => array( $this, 'get_site_name' ),
					)
				);

				$wp_customize->selective_refresh->add_partial(
					'blogdescription',
					array(
						'selector'        => '.site-description',
						'render_callback' => array( $this, 'get_site_description' ),
					)
				);
			}

			/**
			 * Custom controls
			 */
			require_once dirname( __FILE__ ) . '/class-fiftyshadesfurniture-customizer-control-radio-image.php';
			require_once dirname( __FILE__ ) . '/class-fiftyshadesfurniture-customizer-control-arbitrary.php';

			/**
			 * Filter for including additional custom controls.
			 *
			 * @param boolean Include control file.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			if ( apply_filters( 'fiftyshadesfurniture_customizer_more', true ) ) {
				require_once dirname( __FILE__ ) . '/class-fiftyshadesfurniture-customizer-control-more.php';
			}

			/**
			 * Add the typography section
			 */
			$wp_customize->add_section(
				'fiftyshadesfurniture_typography',
				array(
					'title'    => __( 'Typography', 'fiftyshadesfurniture' ),
					'priority' => 45,
				)
			);

			/**
			 * Heading color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_heading_color',
				array(
					/**
					 * Filters for modifying the default heading color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_heading_color', '#484c51' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_heading_color',
					array(
						'label'    => __( 'Heading color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_typography',
						'settings' => 'fiftyshadesfurniture_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Text Color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_text_color',
				array(
					/**
					 * Filters for modifying the default text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_text_color', '#43454b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_text_color',
					array(
						'label'    => __( 'Text color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_typography',
						'settings' => 'fiftyshadesfurniture_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Accent Color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_accent_color',
				array(
					/**
					 * Filters for modifying the default accent color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_accent_color', '#7f54b3' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_accent_color',
					array(
						'label'    => __( 'Link / accent color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_typography',
						'settings' => 'fiftyshadesfurniture_accent_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Hero Heading Color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_hero_heading_color',
				array(
					/**
					 * Filters for modifying the default hero heading color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_hero_heading_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_hero_heading_color',
					array(
						'label'    => __( 'Hero heading color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_typography',
						'settings' => 'fiftyshadesfurniture_hero_heading_color',
						'priority' => 50,
					)
				)
			);

			/**
			 * Hero Text Color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_hero_text_color',
				array(
					/**
					 * Filters for modifying the default hero text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_hero_text_color', '#000000' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_hero_text_color',
					array(
						'label'    => __( 'Hero text color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_typography',
						'settings' => 'fiftyshadesfurniture_hero_text_color',
						'priority' => 60,
					)
				)
			);

			$wp_customize->add_control(
				new Arbitrary_Fiftyshadesfurniture_Control(
					$wp_customize,
					'fiftyshadesfurniture_header_image_heading',
					array(
						'section'  => 'header_image',
						'type'     => 'heading',
						'label'    => __( 'Header background image', 'fiftyshadesfurniture' ),
						'priority' => 6,
					)
				)
			);

			/**
			 * Header Background
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_header_background_color',
				array(
					/**
					 * Filters for modifying the default header background color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_header_background_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_header_background_color',
					array(
						'label'    => __( 'Background color', 'fiftyshadesfurniture' ),
						'section'  => 'header_image',
						'settings' => 'fiftyshadesfurniture_header_background_color',
						'priority' => 15,
					)
				)
			);

			/**
			 * Header text color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_header_text_color',
				array(
					/**
					 * Filters for modifying the default header text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_header_text_color', '#9aa0a7' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_header_text_color',
					array(
						'label'    => __( 'Text color', 'fiftyshadesfurniture' ),
						'section'  => 'header_image',
						'settings' => 'fiftyshadesfurniture_header_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Header link color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_header_link_color',
				array(
					/**
					 * Filters for modifying the default header link color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_header_link_color', '#d5d9db' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_header_link_color',
					array(
						'label'    => __( 'Link color', 'fiftyshadesfurniture' ),
						'section'  => 'header_image',
						'settings' => 'fiftyshadesfurniture_header_link_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Footer section
			 */
			$wp_customize->add_section(
				'fiftyshadesfurniture_footer',
				array(
					'title'       => __( 'Footer', 'fiftyshadesfurniture' ),
					'priority'    => 28,
					'description' => __( 'Customize the look & feel of your website footer.', 'fiftyshadesfurniture' ),
				)
			);

			/**
			 * Footer Background
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_footer_background_color',
				array(
					/**
					 * Filters for modifying the default footer background color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_footer_background_color', '#f0f0f0' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_footer_background_color',
					array(
						'label'    => __( 'Background color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_footer',
						'settings' => 'fiftyshadesfurniture_footer_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Footer heading color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_footer_heading_color',
				array(
					/**
					 * Filters for modifying the default footer heading color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_footer_heading_color', '#494c50' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_footer_heading_color',
					array(
						'label'    => __( 'Heading color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_footer',
						'settings' => 'fiftyshadesfurniture_footer_heading_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Footer text color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_footer_text_color',
				array(
					/**
					 * Filters for modifying the default footer text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_footer_text_color', '#61656b' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_footer_text_color',
					array(
						'label'    => __( 'Text color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_footer',
						'settings' => 'fiftyshadesfurniture_footer_text_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Footer link color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_footer_link_color',
				array(
					/**
					 * Filters for modifying the default footer link color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_footer_link_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_footer_link_color',
					array(
						'label'    => __( 'Link color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_footer',
						'settings' => 'fiftyshadesfurniture_footer_link_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Buttons section
			 */
			$wp_customize->add_section(
				'fiftyshadesfurniture_buttons',
				array(
					'title'       => __( 'Buttons', 'fiftyshadesfurniture' ),
					'priority'    => 45,
					'description' => __( 'Customize the look & feel of your website buttons.', 'fiftyshadesfurniture' ),
				)
			);

			/**
			 * Button background color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_button_background_color',
				array(
					/**
					 * Filters for modifying the default button background color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_button_background_color', '#96588a' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_button_background_color',
					array(
						'label'    => __( 'Background color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_buttons',
						'settings' => 'fiftyshadesfurniture_button_background_color',
						'priority' => 10,
					)
				)
			);

			/**
			 * Button text color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_button_text_color',
				array(
					/**
					 * Filters for modifying the default button text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_button_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_button_text_color',
					array(
						'label'    => __( 'Text color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_buttons',
						'settings' => 'fiftyshadesfurniture_button_text_color',
						'priority' => 20,
					)
				)
			);

			/**
			 * Button alt background color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_button_alt_background_color',
				array(
					/**
					 * Filters for modifying the default button alt background color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_button_alt_background_color', '#2c2d33' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_button_alt_background_color',
					array(
						'label'    => __( 'Alternate button background color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_buttons',
						'settings' => 'fiftyshadesfurniture_button_alt_background_color',
						'priority' => 30,
					)
				)
			);

			/**
			 * Button alt text color
			 */
			$wp_customize->add_setting(
				'fiftyshadesfurniture_button_alt_text_color',
				array(
					/**
					 * Filters for modifying the default button alt text color.
					 *
					 * @param string Hex color value.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_button_alt_text_color', '#ffffff' ),
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'fiftyshadesfurniture_button_alt_text_color',
					array(
						'label'    => __( 'Alternate button text color', 'fiftyshadesfurniture' ),
						'section'  => 'fiftyshadesfurniture_buttons',
						'settings' => 'fiftyshadesfurniture_button_alt_text_color',
						'priority' => 40,
					)
				)
			);

			/**
			 * Layout
			 */
			$wp_customize->add_section(
				'fiftyshadesfurniture_layout',
				array(
					'title'    => __( 'Layout', 'fiftyshadesfurniture' ),
					'priority' => 50,
				)
			);

			$wp_customize->add_setting(
				'fiftyshadesfurniture_layout',
				array(
					/**
					 * Filters for modifying the default layout.
					 *
					 * @param string left/right based on RTL.
					 * @package  fiftyshadesfurniture
					 * @since    2.0.0
					 */
					'default'           => apply_filters( 'fiftyshadesfurniture_default_layout', $layout = is_rtl() ? 'left' : 'right' ),
					'sanitize_callback' => 'fiftyshadesfurniture_sanitize_choices',
				)
			);

			$wp_customize->add_control(
				new Fiftyshadesfurniture_Custom_Radio_Image_Control(
					$wp_customize,
					'fiftyshadesfurniture_layout',
					array(
						'settings' => 'fiftyshadesfurniture_layout',
						'section'  => 'fiftyshadesfurniture_layout',
						'label'    => __( 'General Layout', 'fiftyshadesfurniture' ),
						'priority' => 1,
						'choices'  => array(
							'right' => get_template_directory_uri() . '/assets/images/customizer/controls/2cr.png',
							'left'  => get_template_directory_uri() . '/assets/images/customizer/controls/2cl.png',
						),
					)
				)
			);

			/**
			 * Filter for including additional custom controls.
			 *
			 * @param boolean Add additional sections.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			if ( apply_filters( 'fiftyshadesfurniture_customizer_more', true ) ) {
				$wp_customize->add_section(
					'fiftyshadesfurniture_more',
					array(
						'title'    => __( 'More', 'fiftyshadesfurniture' ),
						'priority' => 999,
					)
				);

				$wp_customize->add_setting(
					'fiftyshadesfurniture_more',
					array(
						'default'           => null,
						'sanitize_callback' => 'sanitize_text_field',
					)
				);

				$wp_customize->add_control(
					new More_Fiftyshadesfurniture_Control(
						$wp_customize,
						'fiftyshadesfurniture_more',
						array(
							'label'    => __( 'Looking for more options?', 'fiftyshadesfurniture' ),
							'section'  => 'fiftyshadesfurniture_more',
							'settings' => 'fiftyshadesfurniture_more',
							'priority' => 1,
						)
					)
				);
			}
		}

		/**
		 * Get all of the Fiftyshadesfurniture theme mods.
		 *
		 * @return array $fiftyshadesfurniture_theme_mods The Fiftyshadesfurniture Theme Mods.
		 */
		public function get_fiftyshadesfurniture_theme_mods() {
			$fiftyshadesfurniture_theme_mods = array(
				'background_color'            => fiftyshadesfurniture_get_content_background_color(),
				'accent_color'                => get_theme_mod( 'fiftyshadesfurniture_accent_color' ),
				'hero_heading_color'          => get_theme_mod( 'fiftyshadesfurniture_hero_heading_color' ),
				'hero_text_color'             => get_theme_mod( 'fiftyshadesfurniture_hero_text_color' ),
				'header_background_color'     => get_theme_mod( 'fiftyshadesfurniture_header_background_color' ),
				'header_link_color'           => get_theme_mod( 'fiftyshadesfurniture_header_link_color' ),
				'header_text_color'           => get_theme_mod( 'fiftyshadesfurniture_header_text_color' ),
				'footer_background_color'     => get_theme_mod( 'fiftyshadesfurniture_footer_background_color' ),
				'footer_link_color'           => get_theme_mod( 'fiftyshadesfurniture_footer_link_color' ),
				'footer_heading_color'        => get_theme_mod( 'fiftyshadesfurniture_footer_heading_color' ),
				'footer_text_color'           => get_theme_mod( 'fiftyshadesfurniture_footer_text_color' ),
				'text_color'                  => get_theme_mod( 'fiftyshadesfurniture_text_color' ),
				'heading_color'               => get_theme_mod( 'fiftyshadesfurniture_heading_color' ),
				'button_background_color'     => get_theme_mod( 'fiftyshadesfurniture_button_background_color' ),
				'button_text_color'           => get_theme_mod( 'fiftyshadesfurniture_button_text_color' ),
				'button_alt_background_color' => get_theme_mod( 'fiftyshadesfurniture_button_alt_background_color' ),
				'button_alt_text_color'       => get_theme_mod( 'fiftyshadesfurniture_button_alt_text_color' ),
			);

			/**
			 * Filters for Fiftyshadesfurniture Theme Mods.
			 *
			 * @param array Associative array of theme mods for color options.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			return apply_filters( 'fiftyshadesfurniture_theme_mods', $fiftyshadesfurniture_theme_mods );
		}

		/**
		 * Get Customizer css.
		 *
		 * @see get_fiftyshadesfurniture_theme_mods()
		 * @return array $styles the css
		 */
		public function get_css() {
			$fiftyshadesfurniture_theme_mods = $this->get_fiftyshadesfurniture_theme_mods();
			/**
			 * Filters for brightening color value.
			 *
			 * @param int Numerical value for brighten amount.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			$brighten_factor = apply_filters( 'fiftyshadesfurniture_brighten_factor', 25 );
			/**
			 * Filters for darkening color value.
			 *
			 * @param int Numerical value for darken amount.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			$darken_factor = apply_filters( 'fiftyshadesfurniture_darken_factor', -25 );

			$styles = '
			.main-navigation ul li a,
			.site-title a,
			ul.menu li a,
			.site-branding h1 a,
			button.menu-toggle,
			button.menu-toggle:hover,
			.handheld-navigation .dropdown-toggle {
				color: ' . $fiftyshadesfurniture_theme_mods['header_link_color'] . ';
			}

			button.menu-toggle,
			button.menu-toggle:hover {
				border-color: ' . $fiftyshadesfurniture_theme_mods['header_link_color'] . ';
			}

			.main-navigation ul li a:hover,
			.main-navigation ul li:hover > a,
			.site-title a:hover,
			.site-header ul.menu li.current-menu-item > a {
				color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['header_link_color'], 65 ) . ';
			}

			table:not( .has-background ) th {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -7 ) . ';
			}

			table:not( .has-background ) tbody td {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -2 ) . ';
			}

			table:not( .has-background ) tbody tr:nth-child(2n) td,
			fieldset,
			fieldset legend {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -4 ) . ';
			}

			.site-header,
			.secondary-navigation ul ul,
			.main-navigation ul.menu > li.menu-item-has-children:after,
			.secondary-navigation ul.menu ul,
			.fiftyshadesfurniture-handheld-footer-bar,
			.fiftyshadesfurniture-handheld-footer-bar ul li > a,
			.fiftyshadesfurniture-handheld-footer-bar ul li.search .site-search,
			button.menu-toggle,
			button.menu-toggle:hover {
				background-color: ' . $fiftyshadesfurniture_theme_mods['header_background_color'] . ';
			}

			p.site-description,
			.site-header,
			.fiftyshadesfurniture-handheld-footer-bar {
				color: ' . $fiftyshadesfurniture_theme_mods['header_text_color'] . ';
			}

			button.menu-toggle:after,
			button.menu-toggle:before,
			button.menu-toggle span:before {
				background-color: ' . $fiftyshadesfurniture_theme_mods['header_link_color'] . ';
			}

			h1, h2, h3, h4, h5, h6, .wc-block-grid__product-title {
				color: ' . $fiftyshadesfurniture_theme_mods['heading_color'] . ';
			}

			.widget h1 {
				border-bottom-color: ' . $fiftyshadesfurniture_theme_mods['heading_color'] . ';
			}

			body,
			.secondary-navigation a {
				color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
			}

			.widget-area .widget a,
			.hentry .entry-header .posted-on a,
			.hentry .entry-header .post-author a,
			.hentry .entry-header .post-comments a,
			.hentry .entry-header .byline a {
				color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['text_color'], 5 ) . ';
			}

			a {
				color: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
			}

			a:focus,
			button:focus,
			.button.alt:focus,
			input:focus,
			textarea:focus,
			input[type="button"]:focus,
			input[type="reset"]:focus,
			input[type="submit"]:focus,
			input[type="email"]:focus,
			input[type="tel"]:focus,
			input[type="url"]:focus,
			input[type="password"]:focus,
			input[type="search"]:focus {
				outline-color: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
			}

			button, input[type="button"], input[type="reset"], input[type="submit"], .button, .widget a.button {
				background-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
				border-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
				color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
			}

			button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .button:hover, .widget a.button:hover {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_background_color'], $darken_factor ) . ';
				border-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_background_color'], $darken_factor ) . ';
				color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
			}

			button.alt, input[type="button"].alt, input[type="reset"].alt, input[type="submit"].alt, .button.alt, .widget-area .widget a.button.alt {
				background-color: ' . $fiftyshadesfurniture_theme_mods['button_alt_background_color'] . ';
				border-color: ' . $fiftyshadesfurniture_theme_mods['button_alt_background_color'] . ';
				color: ' . $fiftyshadesfurniture_theme_mods['button_alt_text_color'] . ';
			}

			button.alt:hover, input[type="button"].alt:hover, input[type="reset"].alt:hover, input[type="submit"].alt:hover, .button.alt:hover, .widget-area .widget a.button.alt:hover {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				border-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_alt_background_color'], $darken_factor ) . ';
				color: ' . $fiftyshadesfurniture_theme_mods['button_alt_text_color'] . ';
			}

			.pagination .page-numbers li .page-numbers.current {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], $darken_factor ) . ';
				color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['text_color'], -10 ) . ';
			}

			#comments .comment-list .comment-content .comment-text {
				background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -7 ) . ';
			}

			.site-footer {
				background-color: ' . $fiftyshadesfurniture_theme_mods['footer_background_color'] . ';
				color: ' . $fiftyshadesfurniture_theme_mods['footer_text_color'] . ';
			}

			.site-footer a:not(.button):not(.components-button) {
				color: ' . $fiftyshadesfurniture_theme_mods['footer_link_color'] . ';
			}

			.site-footer .fiftyshadesfurniture-handheld-footer-bar a:not(.button):not(.components-button) {
				color: ' . $fiftyshadesfurniture_theme_mods['header_link_color'] . ';
			}

			.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6, .site-footer .widget .widget-title, .site-footer .widget .widgettitle {
				color: ' . $fiftyshadesfurniture_theme_mods['footer_heading_color'] . ';
			}

			.page-template-template-homepage.has-post-thumbnail .type-page.has-post-thumbnail .entry-title {
				color: ' . $fiftyshadesfurniture_theme_mods['hero_heading_color'] . ';
			}

			.page-template-template-homepage.has-post-thumbnail .type-page.has-post-thumbnail .entry-content {
				color: ' . $fiftyshadesfurniture_theme_mods['hero_text_color'] . ';
			}

			@media screen and ( min-width: 768px ) {
				.secondary-navigation ul.menu a:hover {
					color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['header_text_color'], $brighten_factor ) . ';
				}

				.secondary-navigation ul.menu a {
					color: ' . $fiftyshadesfurniture_theme_mods['header_text_color'] . ';
				}

				.main-navigation ul.menu ul.sub-menu,
				.main-navigation ul.nav-menu ul.children {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['header_background_color'], -15 ) . ';
				}

				.site-header {
					border-bottom-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['header_background_color'], -15 ) . ';
				}
			}';

			/**
			 * Filters for Fiftyshadesfurniture Customizer CSS.
			 *
			 * @param object Object of CSS rulesets.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			return apply_filters( 'fiftyshadesfurniture_customizer_css', $styles );
		}

		/**
		 * Get Gutenberg Customizer css.
		 *
		 * @see get_fiftyshadesfurniture_theme_mods()
		 * @return array $styles the css
		 */
		public function gutenberg_get_css() {
			$fiftyshadesfurniture_theme_mods = $this->get_fiftyshadesfurniture_theme_mods();
			/**
			 * Filters for darkening color value.
			 *
			 * @param int Numerical value for darken amount.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			$darken_factor = apply_filters( 'fiftyshadesfurniture_darken_factor', -25 );

			// Gutenberg.
			$styles = '
				.wp-block-button__link:not(.has-text-color) {
					color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
				}

				.wp-block-button__link:not(.has-text-color):hover,
				.wp-block-button__link:not(.has-text-color):focus,
				.wp-block-button__link:not(.has-text-color):active {
					color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
				}

				.wp-block-button__link:not(.has-background) {
					background-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
				}

				.wp-block-button__link:not(.has-background):hover,
				.wp-block-button__link:not(.has-background):focus,
				.wp-block-button__link:not(.has-background):active {
					border-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_background_color'], $darken_factor ) . ';
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_background_color'], $darken_factor ) . ';
				}

				.wc-block-grid__products .wc-block-grid__product .wp-block-button__link {
					background-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
					border-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
					color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
				}

				.wp-block-quote footer,
				.wp-block-quote cite,
				.wp-block-quote__citation {
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}

				.wp-block-pullquote cite,
				.wp-block-pullquote footer,
				.wp-block-pullquote__citation {
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}

				.wp-block-image figcaption {
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}

				.wp-block-separator.is-style-dots::before {
					color: ' . $fiftyshadesfurniture_theme_mods['heading_color'] . ';
				}

				.wp-block-file a.wp-block-file__button {
					color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
					background-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
					border-color: ' . $fiftyshadesfurniture_theme_mods['button_background_color'] . ';
				}

				.wp-block-file a.wp-block-file__button:hover,
				.wp-block-file a.wp-block-file__button:focus,
				.wp-block-file a.wp-block-file__button:active {
					color: ' . $fiftyshadesfurniture_theme_mods['button_text_color'] . ';
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_background_color'], $darken_factor ) . ';
				}

				.wp-block-code,
				.wp-block-preformatted pre {
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}

				.wp-block-table:not( .has-background ):not( .is-style-stripes ) tbody tr:nth-child(2n) td {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -2 ) . ';
				}

				.wp-block-cover .wp-block-cover__inner-container h1:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h2:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h3:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h4:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h5:not(.has-text-color),
				.wp-block-cover .wp-block-cover__inner-container h6:not(.has-text-color) {
					color: ' . $fiftyshadesfurniture_theme_mods['hero_heading_color'] . ';
				}

				.wc-block-components-price-slider__range-input-progress {
					--range-color: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
				}

				/* Target only IE11 */
				@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
					.wc-block-components-price-slider__range-input-progress {
						background: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
					}
				}

				.wc-block-components-button:not(.is-link) {
					background-color: ' . $fiftyshadesfurniture_theme_mods['button_alt_background_color'] . ';
					color: ' . $fiftyshadesfurniture_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-components-button:not(.is-link):hover,
				.wc-block-components-button:not(.is-link):focus,
				.wc-block-components-button:not(.is-link):active {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['button_alt_background_color'], $darken_factor ) . ';
					color: ' . $fiftyshadesfurniture_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-components-button:not(.is-link):disabled {
					background-color: ' . $fiftyshadesfurniture_theme_mods['button_alt_background_color'] . ';
					color: ' . $fiftyshadesfurniture_theme_mods['button_alt_text_color'] . ';
				}

				.wc-block-cart__submit-container {
					background-color: ' . $fiftyshadesfurniture_theme_mods['background_color'] . ';
				}

				.wc-block-cart__submit-container::before {
					color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], is_color_light( $fiftyshadesfurniture_theme_mods['background_color'] ) ? -35 : 70, 0.5 ) . ';
				}

				.wc-block-components-order-summary-item__quantity {
					background-color: ' . $fiftyshadesfurniture_theme_mods['background_color'] . ';
					border-color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
					box-shadow: 0 0 0 2px ' . $fiftyshadesfurniture_theme_mods['background_color'] . ';
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}
			';

			/**
			 * Filters for Gutenberg Customizer CSS.
			 *
			 * @param object Object of CSS rulesets.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			return apply_filters( 'fiftyshadesfurniture_gutenberg_customizer_css', $styles );
		}

		/**
		 * Enqueue dynamic colors to use editor blocks.
		 *
		 * @since 2.4.0
		 */
		public function block_editor_customizer_css() {
			$fiftyshadesfurniture_theme_mods = $this->get_fiftyshadesfurniture_theme_mods();

			$styles = '';

			if ( is_admin() ) {
				$styles .= '
				.editor-styles-wrapper {
					background-color: ' . $fiftyshadesfurniture_theme_mods['background_color'] . ';
				}

				.editor-styles-wrapper table:not( .has-background ) th {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -7 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody td {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -2 ) . ';
				}

				.editor-styles-wrapper table:not( .has-background ) tbody tr:nth-child(2n) td,
				.editor-styles-wrapper fieldset,
				.editor-styles-wrapper fieldset legend {
					background-color: ' . fiftyshadesfurniture_adjust_color_brightness( $fiftyshadesfurniture_theme_mods['background_color'], -4 ) . ';
				}

				.editor-post-title__block .editor-post-title__input,
				.editor-styles-wrapper h1,
				.editor-styles-wrapper h2,
				.editor-styles-wrapper h3,
				.editor-styles-wrapper h4,
				.editor-styles-wrapper h5,
				.editor-styles-wrapper h6 {
					color: ' . $fiftyshadesfurniture_theme_mods['heading_color'] . ';
				}

				/* WP <=5.3 */
				.editor-styles-wrapper .editor-block-list__block,
				/* WP >=5.4 */
				.editor-styles-wrapper .block-editor-block-list__block:not(:has(div.has-background-dim)) {
					color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
				}
				/* This following ruleset is a fallback for browsers that do not support the :has() selector. It can be removed once support reaches our requirements. */
				@supports not (selector(:has(*))) {
					.editor-styles-wrapper .block-editor-block-list__block:not(.wp-block-woocommerce-featured-product, .wp-block-woocommerce-featured-category) {
						color: ' . $fiftyshadesfurniture_theme_mods['text_color'] . ';
					}
				}

				.editor-styles-wrapper a,
				.wp-block-freeform.block-library-rich-text__tinymce a {
					color: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
				}

				.editor-styles-wrapper a:focus,
				.wp-block-freeform.block-library-rich-text__tinymce a:focus {
					outline-color: ' . $fiftyshadesfurniture_theme_mods['accent_color'] . ';
				}

				body.post-type-post .editor-post-title__block::after {
					content: "";
				}';
			}

			$styles .= $this->gutenberg_get_css();

			/**
			 * Filters for Gutenberg Block Editor Customizer CSS.
			 *
			 * @param object Object of CSS rulesets.
			 * @package  fiftyshadesfurniture
			 * @since    2.0.0
			 */
			wp_add_inline_style( 'fiftyshadesfurniture-gutenberg-blocks', apply_filters( 'fiftyshadesfurniture_gutenberg_block_editor_customizer_css', $styles ) );
		}

		/**
		 * Add CSS in <head> for styles handled by the theme customizer
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function add_customizer_css() {
			wp_add_inline_style( 'fiftyshadesfurniture-style', $this->get_css() );
		}

		/**
		 * Layout classes
		 * Adds 'right-sidebar' and 'left-sidebar' classes to the body tag
		 *
		 * @param  array $classes current body classes.
		 * @return string[]          modified body classes
		 * @since  1.0.0
		 */
		public function layout_class( $classes ) {
			$left_or_right = get_theme_mod( 'fiftyshadesfurniture_layout' );

			$classes[] = $left_or_right . '-sidebar';

			return $classes;
		}

		/**
		 * Add CSS for custom controls
		 *
		 * This function incorporates CSS from the Kirki Customizer Framework
		 *
		 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
		 * is licensed under the terms of the GNU GPL, Version 2 (or later)
		 *
		 * @link https://github.com/reduxframework/kirki/
		 * @since  1.5.0
		 */
		public function customizer_custom_control_css() {
			?>
			<style>
			.customize-control-radio-image input[type=radio] {
				display: none;
			}

			.customize-control-radio-image label {
				display: block;
				width: 48%;
				float: left;
				margin-right: 4%;
			}

			.customize-control-radio-image label:nth-of-type(2n) {
				margin-right: 0;
			}

			.customize-control-radio-image img {
				opacity: .5;
			}

			.customize-control-radio-image input[type=radio]:checked + label img,
			.customize-control-radio-image img:hover {
				opacity: 1;
			}

			</style>
			<?php
		}

		/**
		 * Get site logo.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_logo() {
			return fiftyshadesfurniture_site_title_or_logo( false );
		}

		/**
		 * Get site name.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_name() {
			return get_bloginfo( 'name', 'display' );
		}

		/**
		 * Get site description.
		 *
		 * @since 2.1.5
		 * @return string
		 */
		public function get_site_description() {
			return get_bloginfo( 'description', 'display' );
		}

		/**
		 * Check if current page is using the Homepage template.
		 *
		 * @since 2.3.0
		 * @return bool
		 */
		public function is_homepage_template() {
			$template = get_post_meta( get_the_ID(), '_wp_page_template', true );

			if ( ! $template || 'template-homepage.php' !== $template || ! has_post_thumbnail( get_the_ID() ) ) {
				return false;
			}

			return true;
		}

	}

endif;

return new Fiftyshadesfurniture_Customizer();
