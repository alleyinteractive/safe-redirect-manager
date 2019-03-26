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
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'guest-author';

	/**
	 * Meta key for storing unique id.
	 *
	 * @var string
	 */
	protected $unique_id_key = 'nid';

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

		// Map additional fields.
		$this->object['display_name']  = $this->source['title'] ?? __( 'Missing Name', 'cpr' );
		$this->object['user_login']    = sanitize_title( "{$this->object['first_name']} {$this->object['last_name']}" );
		$this->object['twitter']       = $this->source['field_twitter']['und'][0]['title'] ?? false;
		$this->object['bio']           = $this->source['body']['und'][0]['value'] ?? false;

		// Map meta.
		$this->object['meta_input'] = [
			'legacy_id' => $this->source['nid'],
		];

		// Log debug data.
		alleypack_log( 'Mapped source to object. Source:', $this->source );
		alleypack_log( 'Object:', $this->object );
	}

	/**
	 * Override method so that post statuses are left alone during sync.
	 */
	public static function mark_existing_content_as_syncing() {
	}

	/**
	 * Override method so that post statuses are left alone during sync.
	 */
	public static function unpublish_unsynced_content() {
	}

	/**
	 * Create or update the post object.
	 *
	 * @return bool Did the object save?
	 */
	public function save_object() {
		global $coauthors_plus;

		// Look for an existing guest author based on NID.
		$posts = get_posts(
			[
				'post_type'        => 'guest-author',
				'posts_per_page'   => 1,
				'fields'           => 'ids',
				'suppress_filters' => false,
				'meta_key'         => 'nid',
				'meta_value'       => $this->get_unique_id(),
			]
		);

		// Existing guest author found.
		if ( ! empty( $posts[0] ) ) {

			// Update the post title if author's display name has changed.
			if ( get_the_title( $posts[0] ) !== $this->object['display_name'] ) {
				wp_update_post(
					[
						'ID'         => $posts[0],
						'post_title' => $this->object['display_name'],
					]
				);
			}

			return true;
		}

		// Create guest author.
		$guest_author_id = $coauthors_plus->guest_authors->create( $this->object );

		// Add various caching and version meta.
		if ( ! $guest_author_id instanceof \WP_Error ) {
			alleypack_log( "Guest author created with ID {$guest_author_id}." );
			$this->object['ID'] = $guest_author_id;
			$this->update_object_cache( $guest_author_id );
			return true;
		}

		alleypack_log( 'Error encountered while creating guest author.', $guest_author_id );
		return false;
	}

	/**
	 * Modify object after it's been saved.
	 */
	public function post_object_save() {
		$guest_author_id = $this->get_object_id();

		if ( is_null( $guest_author_id ) ) {
			return false;
		}

		// Setup meta.
		$meta = [
			'nid'              => $this->object['nid'] ?? '',
			'cap-display_name' => $this->object['display_name'] ?? '',
			'cap-first_name'   => $this->object['first_name'] ?? '',
			'cap-last_name'    => $this->object['last_name'] ?? '',
			'cap-user_email'   => $this->object['user_email'] ?? '',
			'twitter'          => $this->object['twitter'] ?? '',
			'description'      => $this->object['bio'] ?? '',
		];

		foreach ( $meta as $key => $value ) {
			if ( ! empty( $value ) ) {
				update_post_meta( $guest_author_id, $key, $value );
			}
		}

		// Set byline image.
		if ( ! empty( $this->source['field_photo']['uri'] ) && ! has_post_thumbnail( $guest_author_id ) ) {
			$attachment_id = create_attachment_from_url( $this->source['field_photo']['uri'] );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $guest_author_id, $attachment_id );
			}
		}
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

