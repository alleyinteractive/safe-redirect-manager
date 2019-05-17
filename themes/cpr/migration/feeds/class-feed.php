<?php
/**
 * Generic CPR feed for the migration.
 *
 * @package CPR
 */

namespace CPR\Migration;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * Generic CPR feed.
 */
class Feed extends \Alleypack\Sync_Script\Feed {

	/**
	 * Legacy slug for this feed.
	 *
	 * @var string
	 */
	protected $data_slug = '';

	/**
	 * Callback used to modify the mapping built being loaded.
	 *
	 * @param array $mapping Mapping.
	 * @return array
	 */
	public function mapping_filter( array $mapping ) : array {
		return $mapping;
	}

	/**
	 * Load source data using a limit and offset.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return bool Loaded successfully?
	 */
	public function load_source_data_by_limit_and_offset( int $limit, int $offset ) : bool {

		// Begin collecting source data.
		$data = [];

		// Loop through limit.
		while ( $limit > 0 ) {
			$data[] = Migration::instance()->get_source_data_by_offset( $this->data_slug, $offset, [ $this, 'mapping_filter' ] );

			// Increase offset and decrease limit to count up.
			$offset++;
			$limit--;
		}

		$this->set_source_data( $data );
		return $this->has_source_data();
	}

	/**
	 * Load source data using a unique ID.
	 *
	 * @param string $unique_id Unique ID.
	 * @return bool Loaded successfully?
	 */
	public function load_source_data_by_unique_id( string $unique_id ) : bool {
		$data = Migration::instance()->get_source_data_by_id( $this->data_slug, absint( $unique_id ) );
		if ( ! empty( $data ) ) {
			$this->set_source_data( [ $data ] );
		}
		return $this->has_source_data();
	}
}
