<?php
/**
 * Latest Podcast Episodes component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Latest Podcast Episodes.
 */
class Latest_Podcast_Episodes extends \WP_Component\Component {

	use \Alleypack\FM_Module;
	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'latest-podcast-episodes';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading' => $this->get_default_heading(),
		];
	}

	/**
	 * Get the default heading value.
	 *
	 * @return string
	 */
	public function get_default_heading() : string {
		return __( 'Latest Podcast Episodes', 'cpr' );
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading' => new \Fieldmanager_Textfield(
				[
					'default_value' => $this->get_default_heading(),
					'label'         => __( 'Heading', 'cpr' ),
				]
			),
			'episode_ids' => new \Fieldmanager_Zone_Field(
				[
					'add_more_label' => __( 'Add Episode', 'cpr' ),
					'post_limit'     => 4,
					'query_args'     => [
						'post_type' => [ 'podcast-episode' ],
					],
				]
			),
		];
	}

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param  array $fm_data Stored Fieldmanager data.
	 * @return Call_To_Action
	 */
	public function parse_from_fm_data( array $fm_data ) : Latest_Podcast_Episodes {
		$this->set_config( 'heading', (string) ( $fm_data['heading'] ?? $this->get_default_heading() ) );

		$episode_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['episode_ids'] ?? [] ),
			4,
			[
				'post_type' => 'podcast-episode',
			]
		);

		foreach ( $episode_ids as $episode_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )
				->set_post( $episode_id )
				->set_config( 'eyebrow_location', 'top' );
		}

		return $this;
	}
}
