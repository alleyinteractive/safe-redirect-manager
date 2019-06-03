<?php
/**
 * Class for parsing a story item.
 *
 * @package CPR
 */

namespace CPR\Migration\Post_Blocks_Conversion;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Version track the mapping logic. If you modify `map_source_to_object`,
	 * `save_object`, or any other mapping logic, bump this version.
	 *
	 * @var string
	 */
	protected static $mapping_version = '1.1';

	public function should_object_sync() : bool {
		return true;
	}

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
		return get_post_meta( $this->source['ID'] ?? 0, 'alleypack_sync_script_unique_id', true );
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		// Store the post content in meta for easy conversion.
		$legacy_post_content = get_post_meta( $this->get_object_id(), '_legacy_post_content', true );
		if ( empty( $legacy_post_content ) ) {
			update_post_meta( $this->get_object_id(), '_legacy_post_content', $this->source['post_content'] );
			$legacy_post_content = $this->source['post_content'];
		}

		$legacy_post_content = apply_filters( 'cpr_block_converter_replace_media', $legacy_post_content, $this );

		$this->object['post_content'] = ( new Converter( $legacy_post_content ) )->convert_to_block();
	}

	/**
	 * Cache different values to track just the block conversion.
	 */
	public function update_object_cache() {
		update_post_meta( $this->get_object_id(), static::get_mapping_version_key(), static::get_mapping_version() );
	}
}
