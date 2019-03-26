<?php
/**
 * Class for parsing a single person.
 *
 * @package CPR
 */

namespace CPR\Migration\Person;

use function Alleypack\Sync_Script\alleypack_log;
use function Alleypack\create_attachment_from_url;

/**
 * Person Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\User_Feed_Item {

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {
		return true;
	}

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	public function get_unique_id() {
		return $this->source['nid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		// Set the author's first/last name and email.
		$this->parse_author_name();
		$this->parse_email();

		$user_login = $this->object['first_name'] ?? $this->object['last_name'];
		$name       = "{$this->object['first_name']} {$this->object['last_name']}";

		$this->object['user_login']   = sanitize_key( $user_login );
		$this->object['display_name'] = sanitize_title( $name ?? '' );
		$this->object['user_email']   = $this->object['user_email'] ?? '';
		$this->object['description']  = wp_strip_all_tags( $this->source['body']['und'][0]['value'] ?? '' );
		$this->object['user_pass']    = wp_generate_password();
		$this->object['role']         = 'author';

		// Object registered date.
		$date = $this->source['created'] ?? '';
		if ( empty( $date ) ) {
			$date = time();
		}
		$this->object['user_registered'] = date( 'Y-m-d H:i:s', $date );

		// Map meta.
		$this->object['meta_input'] = [
			'legacy_id' => $this->source['nid'],
		];

		// Log debug data.
		alleypack_log( 'Mapped source to object. Source:', $this->source );
		alleypack_log( 'Object:', $this->object );
	}

	/**
	 * Modify object after it's been saved.
	 */
	public function post_object_save() {
		$user_id = $this->get_object_id();

		if ( is_null( $user_id ) ) {
			return false;
		}

		// Create guest author.
		$guest_author_id = $this->create_guest_author_from_user_id( $user_id );

		// Add various caching and version meta.
		if ( $guest_author_id instanceof \WP_Error ) {
			alleypack_log( 'Error encountered while creating guest author.' );
			return false;
		}

		// Save meta.
		update_post_meta( $guest_author_id, 'twitter', $this->source['field_twitter']['und'][0]['title'] ?? false );

		// Set byline image.
		if ( ! empty( $this->source['field_photo']['uri'] ) && ! has_post_thumbnail( $guest_author_id ) ) {
			$attachment_id = create_attachment_from_url( $this->source['field_photo']['uri'] );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $guest_author_id, $attachment_id );
			}
		}

		alleypack_log( "Guest author created with ID {$guest_author_id}. And meta data saved." );
	}

	/**
	 * Do nothing.
	 */
	public static function mark_existing_content_as_syncing() {
	}

	/**
	 * Do nothing.
	 */
	public static function unpublish_unsynced_content() {
	}

	/**
	 * Parse the email address.
	 */
	private function parse_email() {
		$this->object['user_email'] = '';
		
		if ( empty( $this->source['field_email']['und'][0]['value'] ) ) {
			return;
		}
		
		$email = $this->source['field_email']['und'][0]['value'];

		// This definitely isn't an email address.
		if ( ! is_email( $email ) ) {
			return;
		}

		$this->object['user_email'] = $email;
	}

	/**
	 * Parse the author's name into first and last.
	 */
	private function parse_author_name() {
		// Set defaults.
		$this->object['first_name'] = '';
		$this->object['last_name']  = '';

		// Get the author's full name from the source.
		$name = $this->source['title'] ?? '';

		// Bail if we don't have a name.
		if ( empty( $name ) ) {
			return;
		}

		// Remove 'Dr. ' if present.
		if ( substr( strtolower( $name ), 0, 4 ) === 'dr. ' ) {
			$name = substr( $name, 4 );
		}

		// Remove title(s) if present (e.g. ', PhD').
		list( $name ) = explode( ',', $name, 2 );

		// Separate the parts of the name.
		$name_parts = explode( ' ', $name );

		// Set the first name.
		if ( ! empty( $name_parts[0] ) ) {
			$this->object['first_name'] = $name_parts[0];
		}

		// Set the last name. Do not save middle name if present.
		if ( ! empty( $name_parts[2] ) ) {
			$this->object['last_name'] = $name_parts[2];
		} elseif ( ! empty( $name_parts[1] ) ) {
			$this->object['last_name'] = $name_parts[1];
		}
	}
}

