<?php
/**
 * Content Feature component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Content Feature.
 */
class Content_Feature extends \WP_Component\Component {

	use \Alleypack\FM_Module;
	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-feature';

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param  array $fm_data Stored Fieldmanager data.
	 * @return Call_To_Action
	 */
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Content_Feature {

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )->set_post( $content_item_id );
		}

		return $this;
	}
}
