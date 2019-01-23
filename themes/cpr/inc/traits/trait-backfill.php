<?php
/**
 * Backfill trait.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Backfill trait.
 */
trait Backfill {

	/**
	 * Backfill an array of post ids.
	 *
	 * @param array   $content_item_ids Array of post ids.
	 * @param integer $backfill_to      Amount of content needed.
	 * @param array   $backfill_args    Arguments for WP_Query.
	 * @return array
	 */
	public function backfill_content_item_ids( array $content_item_ids = [], $backfill_to = 0, $backfill_args = [] ) {

		// Backfill is disabled, or unnecessary.
		if ( 0 === $backfill_to || $backfill_to <= count( $content_item_ids ) ) {
			return $content_item_ids;
		}

		// Modify backfill args.
		$backfill_args['post__not_in']   = $content_item_ids;
		$backfill_args['posts_per_page'] = $backfill_to - count( $content_item_ids );
		$backfill_args['fields']         = 'ids';

		$backfill_query = new \Alleypack\Unique_WP_Query( $backfill_args );

		if ( ! empty( $backfill_query->posts ) ) {
			$content_item_ids = array_merge( $content_item_ids, $backfill_query->posts );
		}

		return $content_item_ids;
	}
}
