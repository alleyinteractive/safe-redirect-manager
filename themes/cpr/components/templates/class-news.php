<?php
/**
 * News Landing Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

/**
 * News Landing Page template.
 */
class News extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'news-template';

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		$body = new \WP_Components\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get the backfill arguments for this landing page.
	 *
	 * @return array
	 */
	public static function get_backfill_args() {
		return [
			'post_type' => [ 'post', 'podcast-episode' ],
			'tax_query' => [
				'relation' => 'OR',
				// News posts.
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'news',
				],
				// News podcast episodes.
				[
					'taxonomy' => 'podcast',
					'field'    => 'term_id',
					'terms'    => \CPR\get_podcast_term_ids_by_section( 'news' ),
				],
			],
		];
	}

	/**
	 * Get the backfill arguments for the Featured Topic content list, which is
	 * content that's both within the News section and within a particular category.
	 *
	 * @param  int $term_id Term ID.
	 * @return array
	 */
	public static function get_backfill_args_with_cat( int $term_id ) {
		return [
			'post_type' => [ 'post', 'podcast-episode' ],
			'tax_query' => [
				'relation' => 'AND',
				[
					[
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => [ $term_id ],
					],
				],
				[
					'relation' => 'OR',
					// News posts.
					[
						'taxonomy' => 'section',
						'field'    => 'slug',
						'terms'    => 'news',
					],
					// News podcast episodes.
					[
						'taxonomy' => 'podcast',
						'field'    => 'term_id',
						'terms'    => \CPR\get_podcast_term_ids_by_section( 'news' ),
					],
				],
			],
		];
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		$data = (array) get_post_meta( $this->get_post_id(), 'news', true );

		return [
			/**
			 * Featured content with a right sidebar.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'image_size', 'feature_item' )
				->set_config( 'theme', 'feature' )
				->set_config( 'show_excerpt', true )
				->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
				->append_children(
					[
						/**
						 * Right sidebar with an ad.
						 */
						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'right' )
							->set_config( 'has_ad', true )
							->append_child( ( new \CPR\Component\Ad() )->set_config( 'height', 600 ) ),
					]
				),

			/**
			 * Highlighted Content.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'image_size', 'grid_item' )
				->set_config( 'theme', 'grid' )
				->parse_from_fm_data(
					$data['highlighted_content'] ?? [],
					4,
					self::get_backfill_args()
				),

			/**
			 * Newsletter CTA.
			 */
			new \CPR\Component\Modules\Newsletter(),

			/**
			 * "Featured Topic"
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'theme', 'feature' )
				->set_config( 'image_size', 'feature_item' )
				->set_config( 'eyebrow_size', 'small' )
				->set_config( 'show_excerpt', true )
				->set_config( 'heading', get_term( $data['featured_topic']['topic_id'] ?? 0, 'category' )->name ?? '' )
				->set_config( 'heading_link', get_term_link( $data['featured_topic']['topic_id'] ?? 0, 'category' ) )
				->set_config( 'heading_border', true )
				->set_config( 'heading_cta_label', __( 'More Stories', 'cpr' ) )
				->set_config( 'heading_cta_link', get_term_link( $data['featured_topic']['topic_id'] ?? 0, 'category' ) )
				->set_config( 'eyebrow_label', __( 'Featured Topic', 'cpr' ) )
				->parse_from_ids(
					array_slice( $data['featured_topic']['content_item_ids'] ?? [], 0, 1 ),
					1,
					self::get_backfill_args_with_cat( $data['featured_topic']['topic_id'] ?? 0 )
				)
				->append_child(

					/**
					 * Right sidebar.
					 */
					( new \CPR\Component\Sidebar() )
						->set_config( 'position', 'right' )
						->append_child(

							/**
							 * River of additional items.
							 */
							( new \CPR\Component\Modules\Content_List() )
								->set_config( 'layout', 'river' )
								->parse_from_ids(
									array_slice( $data['featured_topic']['content_item_ids'] ?? [], 1 ),
									3,
									self::get_backfill_args_with_cat( $data['featured_topic']['topic_id'] ?? 0 )
								)
						)
				),

			/**
			 * Banner Ad.
			 */
			( new \CPR\Component\Ad() )
				->set_config( 'background_color', '#f8f9fa' )
				->set_config( 'background_padding', true )
				->set_config( 'width', 468 )
				->set_config( 'height', 60 ),

			/**
			 * "More Stories" river.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'layout', 'river' )
				->set_config( 'image_size', 'grid_item' )
				->set_config( 'show_excerpt', true )
				->set_config( 'heading', __( 'More Stories', 'cpr' ) )
				->set_config( 'heading_border', true )
				->set_config( 'call_to_action_label', __( 'More Stories', 'cpr' ) )
				->set_config( 'call_to_action_link', home_url( '/section/news/' ) )
				->parse_from_ids(
					[],
					8,
					self::get_backfill_args()
				)
				->append_child(
					/**
					 * Right Sidebar.
					 */
					( new \CPR\Component\Sidebar() )
						->set_config( 'position', 'right' )
						->set_config( 'has_ad', true )
						->append_children(
							[
								/**
								 * River of content "Across Colorado"
								 */
								( new \CPR\Component\Modules\Content_List() )
									->set_config( 'layout', 'river' )
									->set_config( 'heading', __( 'Across Colorado', 'cpr' ) )
									->parse_from_ids(
										[],
										4,
										[] // TODO: Determine what kind of content this actually is.
									),

								/**
								 * Colorado Wonders question form.
								 */
								// new \CPR\Component\Colorado_Wonders(),.

								/**
								 * Advertisement.
								 */
								new \CPR\Component\Ad(),
							]
						)
				),
		];
	}

	/**
	 * Add additional FM fields to a landing page.
	 *
	 * @param  array $fields FM fields.
	 * @return array
	 */
	public static function landing_page_fields( $fields ) {
		$fields['news'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'News', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'news',
				],
				'children' => [
					'featured_content' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Featured Content', 'cpr' ),
							'children' => [
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'add_more_label' => __( 'Add Content', 'cpr' ),
										'label'          => __( 'Featured Story', 'cpr' ),
										'post_limit'     => 1,
										'query_args'     => self::get_backfill_args(),
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
										'query_args'     => self::get_backfill_args(),
									]
								),
							],
						]
					),
					'featured_topic' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Featured Topic', 'cpr' ),
							'children' => [
								'topic_id' => new \Fieldmanager_Select(
									[
										'label'       => __( 'Topic', 'cpr' ),
										'description' => __( 'Begin typing to select a topic.', 'cpr' ),
										'datasource'  => new \Fieldmanager_Datasource_Term(
											[
												'taxonomy'               => 'category',
												'taxonomy_save_to_terms' => false,
												'only_save_to_taxonomy'  => false,
											]
										),
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'post_limit'     => 4,
										'query_args' => self::get_backfill_args(),
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
