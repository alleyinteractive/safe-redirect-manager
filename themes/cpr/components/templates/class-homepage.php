<?php
/**
 * Homepage Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * Homepage template.
 */
class Homepage extends \WP_Component\Component {

	use \WP_Component\WP_Post;
	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'landing-page';

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		$body = new \WP_Component\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		$data = (array) get_post_meta( $this->get_post_id(), 'homepage', true );
		return [
			/**
			 * Featured content with a left and right sidebar.
			 */
			( new \CPR\Component\Modules\Content_Feature() )
				->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
				->append_children(
					[
						/**
						 * Content river for "Top Headlines"
						 */
						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'left' )
							->append_child(
								( new \CPR\Component\Modules\Content_River() )
									// Modify the source data so the component
									// can parse more easily.
									->parse_from_fm_data(
										[
											'content_item_ids' => $data['featured_content']['top_headlines_content_item_ids'] ?? [],
										],
										4
									)
									->set_config( 'heading', __( 'Top Headlines' ) )
							),

						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'right' ),
							// ->append_child( new \CPR\Component\Ad() )
					]
				),

			( new \CPR\Component\Modules\Content_Grid() )
				->parse_from_fm_data( $data['highlighted_content'] ?? [], 4 )
				->set_config( 'call_to_action_label', __( 'All Stories', 'cpr' ) )
				->set_config( 'call_to_action_link', home_url( '/all/' ) ),

			/**
			 * Latest podcast episodes.
			 */
			( new \CPR\Component\Modules\Latest_Podcast_Episodes() )
				->parse_from_fm_data( $data['latest_podcast_episodes'] ?? [] ),

			/**
			 * Newsletter CTA.
			 */
			new \CPR\Component\Modules\Newsletter(),

			/**
			 * "More Stories" content grid with a sidebar for Colorado Wonders and an ad.
			 */
			( new \CPR\Component\Modules\Content_Grid() )
				->parse_from_fm_data( $data['more_stories'] ?? [], 6 )
				->set_config( 'heading', __( 'More Stories', 'cpr' ) )
				->append_child(
					( new \CPR\Component\Sidebar() )
						->set_config( 'position', 'right' )
						// ->append_children(
						// 	[
						// 		new \CPR\Component\Colorado_Wonders(),
						// 		new \CPR\Component\Ad(),
						// 	]
						// )
				),

			/**
			 * Banner Ad.
			 */
			// new \CPR\Component\Ad(),
		];
	}

	/**
	 * Add additional FM fields to a landing page.
	 *
	 * @param  array $fields FM fields.
	 * @return array
	 */
	public static function landing_page_fields( $fields ) {
		$fields['homepage'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'Homepage', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'homepage',
				],
				'children' => [
					'featured_content' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Featured Content', 'cpr' ),
							'children' => [
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'add_more_label' => __( 'Add Content', 'cpr' ),
										'label' => __( 'Featured Story', 'cpr' ),
										'post_limit'     => 1,
										'query_args'     => [
											'post_type' => [ 'post', 'podcast-episode' ],
										],
									]
								),
								'top_headlines_content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label' => __( 'Top Headlines', 'cpr' ),
										'add_more_label' => __( 'Add Content', 'cpr' ),
										'post_limit'     => 4,
										'query_args'     => [
											'post_type' => [ 'post', 'podcast-episode' ],
										],
									]
								),
							],
						]
					),
					'highlighted_content' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Highlighted Content', 'cpr' ),
							'children' => [
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'add_more_label' => __( 'Add Content', 'cpr' ),
										'post_limit'     => 4,
										'query_args'     => [
											'post_type' => [ 'post', 'podcast-episode' ],
										],
									]
								),
							]
						]
					),
					'latest_podcast_episodes' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Latest Podcast Episodes', 'cpr' ),
							'children' => ( new \CPR\Component\Modules\Latest_Podcast_Episodes() )->get_fm_fields(),
						]
					),
				],
			]
		);
		return $fields;
	}
}
