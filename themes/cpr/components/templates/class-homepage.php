<?php
/**
 * Homepage Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Homepage template.
 */
class Homepage extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'landing-page';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body           = new \WP_Components\Body();
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
			( new \CPR\Components\Feature() )
				->set_theme( 'three-column' )
				->append_children(
					[
						/**
						 * Left sidebar with a river of content items.
						 */
						( new \CPR\Components\Sidebar() )
							->append_child(
								/**
								 * River content list for "Top Headlines"
								 */
								( new \CPR\Components\Modules\Content_List() )
									// Modify the source data so the component
									// can parse more easily.
									->set_config( 'eyebrow_label', __( 'Top Headlines', 'cpr' ) )
									->parse_from_fm_data(
										[
											'content_item_ids' => $data['featured_content']['top_headlines_content_item_ids'] ?? [],
										],
										4
									)
							)
							->set_theme( 'left' )
							->set_child_themes(
								[
									'content-list'         => 'river',
									'content-list-eyebrow' => 'black',
									'content-item'         => 'river',
									'eyebrow'              => 'small',
								]
							),
						/**
						 * Featured content with a left and right sidebar.
						 */
						( new \CPR\Components\Modules\Content_List() )
							->set_config( 'image_size', 'feature_item' )
							->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
							->set_theme( 'feature' )
							->set_child_themes(
								[
									'content-item' => 'featurePrimary',
									'eyebrow'      => 'small',
								]
							),

						/**
						 * Right sidebar with an ad.
						 */
						( new \CPR\Components\Sidebar() )
							->append_child( ( new \CPR\Components\Ad() )->set_config( 'height', 600 ) )
							->set_theme( 'right' ),
					]
				),

			/**
			 * Highlighted Content.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->parse_from_fm_data( $data['highlighted_content'] ?? [], 4 )
				->set_config( 'call_to_action_label', __( 'All Stories', 'cpr' ) )
				->set_config( 'call_to_action_link', home_url( '/all/' ) )
				->set_theme( 'grid' )
				->set_child_themes(
					[
						'content-item' => 'gridPrimary',
						'eyebrow'      => 'small',
					]
				),


			/**
			 * Latest podcast episodes.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->parse_from_fm_data(
					$data['latest_podcast_episodes'] ?? [],
					4,
					[
						'post_type' => 'podcast-episode',
					]
				)
				->set_theme( 'grid' )
				->set_child_themes(
					[
						'content-item' => 'gridSecondary',
						'eyebrow'      => 'large',
					]
				),

			/**
			 * Newsletter CTA.
			 */
			new \CPR\Components\Modules\Newsletter(),

			/**
			 * Playlists for Classical and OpenAir.
			 *
			 * @todo Build this component. Determine if we can reuse another component.
			 */

			/**
			 * "More Stories" content grid with a sidebar for Colorado Wonders and an ad.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->parse_from_fm_data( $data['more_stories'] ?? [], 6 )
				->set_config( 'heading', __( 'More Stories', 'cpr' ) )
				->set_config( 'heading_border', true )
				->set_theme( 'grid' )
				->set_child_themes(
					[
						'content-item' => 'grid',
						'eyebrow'      => 'small',
						'sidebar'      => 'right',
					]
				)
				->append_child(
					/**
					 * Sidebar.
					 */
					( new \CPR\Components\Sidebar() )
						->append_children(
							[
								/**
								 * Colorado Wonders question form.
								 */
								// new \CPR\Components\Colorado_Wonders(),.

								/**
								 * Advertisement.
								 */
								new \CPR\Components\Ad(),
							]
						)
				),

			/**
			 * Banner Ad.
			 */
			new \CPR\Components\Ad(),
		];
	}

	/**
	 * Add additional FM fields to a landing page.
	 *
	 * @param array $fields FM fields.
	 * @return array
	 */
	public static function landing_page_fields( array $fields ) : array {
		$fields['homepage'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'Homepage', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'homepage',
				],
				'children'   => [
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
							],
						]
					),
					'latest_podcast_episodes' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Latest Podcast Episodes', 'cpr' ),
							'children' => [
								'heading' => new \Fieldmanager_Textfield(
									[
										'default_value' => __( 'Latest Podcast Episodes', 'cpr' ),
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
							],
						]
					),
				],
			]
		);
		return $fields;
	}
}
