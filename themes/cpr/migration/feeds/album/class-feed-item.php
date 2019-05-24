<?php
/**
 * Class for parsing an album item.
 *
 * @package CPR
 */

namespace CPR\Migration\Album;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	use \CPR\Migration\Traits\Story;

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'album';

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
		$this->set_basics();
		$this->migrate_meta();
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() {
		$this->migrate_artists();
		$this->migrate_artwork();
		$this->migrate_labels();

		// Album year.
		if ( isset( $this->source['field_release_date']['und'][0]['value'] ) ) {
			update_post_meta( $this->get_object_id(), 'year', $this->source['field_release_date']['und'][0]['value'] );
		}

		// Music type (these should all be `92` for the top-30).
		if ( isset( $this->source['field_music_type']['und'][0]['target_id'] ) ) {
			update_post_meta( $this->get_object_id(), 'type', $this->source['field_music_type']['und'][0]['target_id'] );
		}

		return true;
	}

	/**
	 * Download and set the artwork as the featured image.
	 */
	public function migrate_artwork() {

		// Logo has already been migrated, or doesn't exist.
		if (
			has_post_thumbnail( $this->get_object_id() )
			|| empty( $this->source['field_album_artwork']['und'][0]['filename'] ?? '' )
		) {
			return;
		}

		$attachment_id = \Alleypack\create_attachment_from_url( $this->get_artwork_url() );
		if ( ! $attachment_id instanceof \WP_Error ) {
			update_post_meta( $this->get_object_id(), '_thumbnail_id', $attachment_id );
		}
	}

	/**
	 * Return the artwork url for this underwriter.
	 *
	 * @return string
	 */
	public function get_artwork_url() {

		// Get filename.
		$filename = $this->source['field_album_artwork']['und'][0]['filename'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		return 'https://www.cpr.org/sites/default/files/styles/cover/public/album_art/' . $filename;
	}

	/**
	 * Migrate album artists.
	 */
	public function migrate_artists() {

		// Extract legacy ids.
		$tids = wp_list_pluck( ( $this->source['field_music_artist']['und'] ?? [] ), 'tid' );

		// Create a term for each one.
		$term_ids = array_filter( array_map(
			function( $tid ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'taxonomy', $tid );
				return \CPR\Migration\Artist\Feed_Item::get_or_create_object_from_source( $source )->term_id ?? 0;
			},
			$tids
		) );

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $this->get_object_id(), $term_ids, 'artist' );
		}
	}

	/**
	 * Migrate album labels.
	 */
	public function migrate_labels() {

		// Extract legacy ids.
		$tids = wp_list_pluck( ( $this->source['field_music_label']['und'] ?? [] ), 'tid' );

		// Create a term for each one.
		$term_ids = array_filter( array_map(
			function( $tid ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'taxonomy', $tid );
				return \CPR\Migration\Label\Feed_Item::get_or_create_object_from_source( $source )->term_id ?? 0;
			},
			$tids
		) );

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $this->get_object_id(), $term_ids, 'label' );
		}
	}
}
