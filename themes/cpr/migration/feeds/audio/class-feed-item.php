<?php
/**
 * Class for parsing an audio file.
 *
 * @package CPR
 */

namespace CPR\Migration\Audio;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Feed_Item {

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {
		return true;
	}

	/**
	 * Attempt to get the object ID.
	 *
	 * @return int|null
	 */
	public function get_object_id() : ?int {
		return $this->object['ID'] ?? false;
	}

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	public function get_unique_id() {
		return $this->source['nid'];
	}

	/**
	 * Return the default state of the feed item.
	 *
	 * @param string $unique_id Unique ID.
	 * @return null|WP_Post
	 */
	public static function get_object_by_unique_id( $unique_id ) : ?WP_Post {
		return null;
	}

	/**
	 * Map the source to a new object.
	 */
	public function map_source_to_object() {}

	/**
	 * Get the attachment id from the source field slug.
	 *
	 * @param string $field Field key.
	 * @param array  $args  Arguments for `create_attachment_from_url`.
	 * @return mixed
	 */
	public function get_attachment_id_by_field( string $field, $args ) {

		$source_url = $this->get_source_url_by_field( $field );
		if ( empty( $source_url ) ) {
			return null;
		}

		// Add additional meta data.
		$args['meta']['type']                             = $field;
		$args['meta']['alleypack_attachments_legacy_url'] = $source_url;

		$query = new \WP_Query(
			[
				'post_type'   => 'attachment',
				'post_status' => 'any',
				'meta_query'  => [
					[
						'key'   => 'alleypack_attachments_legacy_url',
						'value' => $source_url,
					],
					[
						'key'   => 'type',
						'value' => $field,
					],
				],
			]
		);

		// Audio has already been migrated, return the id.
		if ( 0 !== ( $query->post->ID ?? 0 ) ) {
			foreach ( $args['meta'] as $key => $value ) {
				update_post_meta( $query->post->ID, $key, $value );
			}
			return $query->post->ID ?? null;
		}

		// Create the audio file.
		$attachment_id = \Alleypack\create_attachment_from_url( $source_url, $args );

		// Was there an error?
		if ( $attachment_id instanceof \WP_Error ) {
			return null;
		}

		return $attachment_id;
	}

	/**
	 * Fires after the object saves.
	 */
	public function post_object_save() {

		$args = [
			'title' => $this->source['title'] ?? '',
			'meta'  => [
				'legacy_id' => $this->source['nid'],
			],
		];

		// Migrate all the audio over.
		$this->object['aac_id'] = $this->get_attachment_id_by_field( 'field_aac_file', $args );
		$this->object['mp3_id'] = $this->get_attachment_id_by_field( 'field_mp3_file', $args );
		$this->object['npr_id'] = $this->get_attachment_id_by_field( 'field_npr_file', $args );
		$this->object['wav_id'] = $this->get_attachment_id_by_field( 'field_wav_file', $args );

		array_map(
			function( $attachment_id ) {
				foreach ( $this->object as $audio_slug => $audio_attachment_id ) {
					update_post_meta( $attachment_id, $audio_slug, $audio_attachment_id );
				}
			},
			$this->object
		);
	}

	/**
	 * Get the source URL for this attachment.
	 *
	 * @param string $field Field key for this attachment.
	 * @return null|string
	 */
	public function get_source_url_by_field( string $field ) : ?string {

		// Get filename.
		$filename = $this->source[ $field ]['und'][0]['uri'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		// Remove protocol.
		$filename = str_replace( 'public://', '', $filename );

		return 'https://www.cpr.org/sites/default/files/' . $filename;
	}

	/**
	 * We manage the state of the 3 audio files outside the normal flow.
	 *
	 * @return bool
	 */
	public function save_object() : bool {
		return true;
	}

	/**
	 * Store various meta data used for caching.
	 */
	public function update_object_cache() {
		// Silence is golden.
	}
}
