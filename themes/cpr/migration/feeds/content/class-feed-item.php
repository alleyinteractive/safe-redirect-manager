<?php
/**
 * Class for parsing post content into blocks.
 *
 * @package CPR
 */

namespace CPR\Migration\Content;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = [
		'post',
		'podcast-episode',
		'show-episode',
		'show-segment',
		'press-release',
	];

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {
		return true;
	}

	/**
	 * Version track the mapping logic. If you modify `map_source_to_object`,
	 * `save_object`, or any other mapping logic, bump this version.
	 *
	 * @var string
	 */
	protected static $mapping_version = '1.3';

	/**
	 * Meta key for storing mapping version key.
	 *
	 * @var string
	 */
	protected static $mapping_version_key = 'cpr_block_conversion_mapping';

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
	 * Map source data to the object.
	 */
	public function map_source_to_object() {
		static::migrate_post( $this->object['ID'], true );
		// Get, filter, convert, and save the post content.
		// $source_post_content          = $this->source['body']['und'][0]['value'] ?? '';
		// $source_post_content          = apply_filters( 'cpr_block_converter_replace_media', $source_post_content, $this );
		// $source_post_content          = ( new Converter( $source_post_content ) )->convert_to_block();
		// $this->object['post_content'] = ( new Converter( '' ) )->remove_empty_blocks( $source_post_content );
	}

	/**
	 * Cache different values to track just the block conversion.
	 */
	public function update_object_cache() {
		// update_post_meta( $this->get_object_id(), static::get_mapping_version_key(), static::get_mapping_version() );
	}


	/**
	 * Migrate the content for a single post.
	 *
	 * @param int     $post_id Post ID.
	 * @param boolean $force   Ignore versioning.
	 * @return boolean
	 */
	public static function migrate_post( $post_id, $force = false ) {

		if ( class_exists( '\WP_CLI' ) ) {
			\WP_CLI::line( "----- Syncing {$post_id} ------" );
		}

		// Check the version.
		$current_version = get_post_meta( $post_id, 'cpr_block_conversion_mapping', true );
		$ideal_version   = self::get_mapping_version();
		if ( $current_version === $ideal_version && ! $force ) {
			if ( class_exists( '\WP_CLI' ) ) {
				\WP_CLI::line( "Already up to date - {$current_version}" );
			}
			return true;
		}

		// Get the source data.
		$legacy_type = get_post_meta( $post_id, 'legacy_type', true );
		$legacy_id   = get_post_meta( $post_id, 'legacy_id', true );
		$source      = [];
		switch ( $legacy_type ) {
			case 'story':
			default:
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( $legacy_type, $legacy_id );
				break;
		}

		// Validate source data.
		$legacy_content = $source['body']['und'][0]['value'] ?? '';
		if ( empty( $legacy_content ) ) {
			if ( class_exists( '\WP_CLI' ) ) {
				\WP_CLI::warning( "Legacy body content missing." );
			}
			return false;
		}

		// Convert.
		$blocked_content = apply_filters( 'cpr_block_converter_replace_media', $legacy_content );
		$blocked_content = ( new Converter( $blocked_content ) )->convert_to_block();
		$blocked_content = ( new Converter( '' ) )->remove_empty_blocks( $blocked_content );

		// Update
		wp_update_post(
			[
				'ID'           => $post_id,
				'post_content' => $blocked_content,
			]
		);

		if ( class_exists( '\WP_CLI' ) ) {
			\WP_CLI::success( "Updated mapping." );
		}

		update_post_meta( $post_id, 'cpr_block_conversion_mapping', $ideal_version );
	}
}
