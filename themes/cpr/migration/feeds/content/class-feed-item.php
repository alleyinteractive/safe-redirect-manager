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
	protected static $mapping_version = '1.2';

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
		// Get, filter, convert, and save the post content.
		$source_post_content          = $this->source['body']['und'][0]['value'] ?? '';
		$source_post_content          = apply_filters( 'cpr_block_converter_replace_media', $source_post_content, $this );
		$source_post_content          = ( new Converter( $source_post_content ) )->convert_to_block();
		$this->object['post_content'] = ( new Converter( '' ) )->remove_empty_blocks( $source_post_content );
	}

	/**
	 * Cache different values to track just the block conversion.
	 */
	public function update_object_cache() {
		update_post_meta( $this->get_object_id(), static::get_mapping_version_key(), static::get_mapping_version() );
	}
}
