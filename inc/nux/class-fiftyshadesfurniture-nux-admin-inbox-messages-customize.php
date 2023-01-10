<?php
/**
 * Fiftyshadesfurniture NUX Admin Inbox Messages.
 *
 * @package  fiftyshadesfurniture
 * @since    3.0.0
 */

use Automattic\WooCommerce\Admin\Notes\Note;
use Automattic\WooCommerce\Admin\Notes\NoteTraits;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Fiftyshadesfurniture_NUX_Admin_Inbox_Messages_Customize' ) ) :
	/**
	 * The initial Fiftyshadesfurniture inbox Message.
	 */
	class Fiftyshadesfurniture_NUX_Admin_Inbox_Messages_Customize {

		use NoteTraits;

		/**
		 * Name of the note for use in the database.
		 */
		const NOTE_NAME = 'fiftyshadesfurniture-customize';

		/**
		 * Get the note.
		 *
		 * @return Note
		 */
		public static function get_note() {
			$note = new Note();
			$note->set_title( __( 'Design your store with Fiftyshadesfurniture 🎨', 'fiftyshadesfurniture' ) );
			$note->set_content( __( 'Visit the Fiftyshadesfurniture settings page to start setup and customization of your shop.', 'fiftyshadesfurniture' ) );
			$note->set_type( Note::E_WC_ADMIN_NOTE_INFORMATIONAL );
			$note->set_name( self::NOTE_NAME );
			$note->set_content_data( (object) array() );
			$note->set_source( 'fiftyshadesfurniture' );
			$note->add_action(
				'customize-store-with-fiftyshadesfurniture',
				__( 'Let\'s go!', 'fiftyshadesfurniture' ),
				admin_url( 'themes.php?page=fiftyshadesfurniture-welcome' ),
				Note::E_WC_ADMIN_NOTE_ACTIONED,
				true
			);
			return $note;
		}
	}
endif;
