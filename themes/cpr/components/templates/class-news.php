<?php
/**
 * News Landing Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

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
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body           = ( new \WP_Components\Body() )
			->set_config( 'body_classes', 'news' );
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get the backfill arguments for this landing page.
	 *
	 * @return array
	 */
	public static function get_backfill_args() : array {
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
	public static function get_backfill_args_with_cat( int $term_id ) : array {
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
		$featured_topic_term = get_term( $data['featured_topic']['topic_id'] ?? 0, 'category' );

		return [
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->append_children(
					[
						/**
						 * Featured content with a right sidebar.
						 */
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'align_item_content' => 'left',
									'image_size'         => 'feature_item',
									'show_excerpt'       => true,
								]
							)
							->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
							->set_theme( 'feature' )
							->set_child_themes(
								[
									'content-item'  => 'featureSecondary',
									'content-title' => 'feature',
									'eyebrow'       => 'small',
								]
							),
						/**
						 * Right sidebar with an ad.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->set_config( 'has_ad', true )
							->append_children(
								[
									/**
									 * Station Playlist.
									 */
									( new \CPR\Components\Audio\Live_Stream() )
										->set_source( 'news' )
										->set_config( 'count', 1 )
										->set_theme( 'sidebar' ),
									( new \CPR\Components\Advertising\Ad_Unit() )
										->configure_ad_slot( 'CPR3-Inst-News-Shared-300x600' ),
								]
							),

					]
				),

			/**
			 * Newsletter CTA.
			 */
			new \CPR\Components\Modules\Newsletter(),

			/**
			 * Highlighted Content.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->append_child(
					( new \CPR\Components\Modules\Content_List() )
						->set_config( 'image_size', 'grid_item' )
						->parse_from_fm_data(
							$data['highlighted_content'] ?? [],
							4,
							self::get_backfill_args()
						)
						->set_theme( 'gridLarge' )
						->set_child_themes(
							[
								'content-item'  => 'grid',
								'content-title' => 'grid',
								'eyebrow'       => 'small',
							]
						)
				),

			/**
			 * "Featured Topic"
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->merge_config(
					[
						'heading'           => ( $featured_topic_term instanceof \WP_Term ) ? $featured_topic_term->name : '',
						'heading_cta_label' => __( 'More Stories', 'cpr' ),
						'heading_cta_link'  => get_term_link( $data['featured_topic']['topic_id'] ?? 0, 'category' ),
						'heading_link'      => get_term_link( $data['featured_topic']['topic_id'] ?? 0, 'category' ),
					]
				)
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'eyebrow_label'     => __( 'Featured Topic', 'cpr' ),
									'eyebrow_size'      => 'small',
									'image_size'        => 'feature_item_small',
									'show_excerpt'      => true,
								]
							)
							->parse_from_ids(
								array_slice( $data['featured_topic']['content_item_ids'] ?? [], 0, 1 ),
								1,
								self::get_backfill_args_with_cat( $data['featured_topic']['topic_id'] ?? 0 )
							)
							->set_theme( 'featureTerm' )
							->set_child_themes(
								[
									'content-item'  => 'featureTerm',
									'content-title' => 'featureSecondary',
									'eyebrow'       => 'small',
								]
							),
						/**
						 * Right sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->append_child(

								/**
								 * River of additional items.
								 */
								( new \CPR\Components\Modules\Content_List() )
									->parse_from_ids(
										array_slice( $data['featured_topic']['content_item_ids'] ?? [], 1 ),
										3,
										self::get_backfill_args_with_cat( $data['featured_topic']['topic_id'] ?? 0 )
									)
							)
							->set_child_themes(
								[
									'content-list'  => 'river',
									'content-item'  => 'river',
									'content-title' => 'grid',
									'eyebrow'       => 'small',
								]
							),
					]
				),

			/**
			 * "More Stories" river.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'twoColumn' )
				->set_config( 'heading', __( 'More Stories', 'cpr' ) )
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'theme'                => 'riverFull',
									'image_size'           => 'grid_item',
									'show_excerpt'         => true,
									'call_to_action_label' => __( 'More Stories', 'cpr' ),
									'call_to_action_link'  => home_url( '/news/all/' ),
								]
							)
							->parse_from_ids(
								[],
								8,
								self::get_backfill_args()
							)
							->set_theme( 'riverFull' )
							->set_child_themes(
								[
									'content-item'  => 'riverFull',
									'content-title' => 'featureSecondary',
									'eyebrow'       => 'small',
								]
							),

						/**
						 * Right Sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->set_sidebar( 'news-sidebar' )
							->prepend_child(
								/**
								 * Advertisement.
								 */
								new \CPR\Components\Advertising\Ad_Unit()
							),
					]
				),
		];
	}

	/**
	 * Add additional FM fields to a landing page.
	 *
	 * @param  array $fields FM fields.
	 * @return array
	 */
	public static function landing_page_fields( $fields ) : array {
		$fields['news'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'News', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'news',
				],
				'children'   => [
					'featured_content'    => new \Fieldmanager_Group(
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
					'featured_topic'      => new \Fieldmanager_Group(
						[
							'label'    => __( 'Featured Topic', 'cpr' ),
							'children' => [
								'topic_id'         => new \Fieldmanager_Select(
									[
										'label'       => __( 'Topic', 'cpr' ),
										'description' => __( 'Begin typing to select a topic.', 'cpr' ),
										'datasource'  => new \Fieldmanager_Datasource_Term(
											[
												'taxonomy' => 'category',
												'taxonomy_save_to_terms' => false,
												'only_save_to_taxonomy' => false,
											]
										),
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'post_limit' => 4,
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
