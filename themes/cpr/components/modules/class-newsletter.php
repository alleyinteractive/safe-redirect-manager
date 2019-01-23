<?php
/**
 * Newsletter component.
 *
 * @package CPR
 */

namespace CPR\Component\Modules;

/**
 * Newsletter.
 */
class Newsletter extends \WP_Component\Component {

	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'newsletter';

	/**
	 * Get the default heading.
	 *
	 * @return string
	 */
	public function get_default_heading() {
		return __( 'News That Matters, Delivered To Your Inbox', 'cpr' );
	}

	/**
	 * Get the default tagline.
	 *
	 * @return string
	 */
	public function get_default_tagline() {
		return __( 'Sign up for a smart, compelling, and sometimes funny take on your daily news briefing.', 'cpr' );
	}

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		$settings = get_option( 'cpr-settings' );
		return [
			'heading' => $settings['engagement']['newsletter']['heading'] ?? $this->get_default_heading(),
			'tagline' => $settings['engagement']['newsletter']['tagline'] ?? $this->get_default_tagline(),
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_heading(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Heading', 'cpr' ),
				]
			),
			'tagline' => new \Fieldmanager_Textarea(
				[
					'attributes'    => [
						'style' => 'width: 100%;',
						'rows'  => 2,
					],
					'default_value' => $this->get_default_tagline(),
					'description'   => __( 'HTML is supported. Use <strong>, <em>, and <a> as needed.', 'cpr' ),
					'label'         => __( 'Tagline', 'cpr' ),
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
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Content_Grid {
		$this->set_config( 'heading', (string) ( $fm_data['heading'] ?? '' ) );
		$this->set_config( 'heading_link', (string) ( $fm_data['heading_link'] ?? '' ) );
		$this->set_config( 'call_to_action_label', (string) ( $fm_data['call_to_action_label'] ?? '' ) );
		$this->set_config( 'call_to_action_link', (string) ( $fm_data['call_to_action_link'] ?? '' ) );

		$content_item_ids = (array) ( $fm_data['content_item_ids'] ?? [] );

		// Determine if we need to backfill.
		if ( 0 !== $backfill_to && $backfill_to !== count( $content_item_ids ) ) {

			// Modify backfill args.
			$backfill_args['post__not_in']   = $content_item_ids;
			$backfill_args['posts_per_page'] = $backfill_to - count( $content_item_ids );
			$backfill_args['fields']         = 'ids';

			$backfill_query = new \Alleypack\Unique_WP_Query( $backfill_args );

			if ( ! empty( $backfill_query->posts ) ) {
				$content_item_ids = array_merge( $content_item_ids, $backfill_query->posts );
			}
		}

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = ( new \CPR\Component\Content_Item() )->set_post( $content_item_id );
		}

		return $this;
	}
}
