<?php
/**
 * Content River component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Content River.
 */
class Content_River extends \WP_Component\Component {

	use \Alleypack\FM_Module;
	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-river';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading'              => '',
			'heading_link'         => '',
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading'              => new \Fieldmanager_Textfield( __( 'Heading', 'cpr' ) ),
			'heading_link'         => new \Fieldmanager_Link( __( 'Heading Link', 'cpr' ) ),
			'content_item_ids'     => new \Fieldmanager_Zone_Field(),
		];
	}

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param  array $fm_data Stored Fieldmanager data.
	 * @return Call_To_Action
	 */
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Content_River {
		$this->set_config( 'heading', (string) ( $fm_data['heading'] ?? '' ) );
		$this->set_config( 'heading_link', (string) ( $fm_data['heading_link'] ?? '' ) );

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
