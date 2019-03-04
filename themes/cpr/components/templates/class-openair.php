<?php
/**
 * OpenAir Landing Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * OpenAir Landing Page template.
 */
class Openair extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'openair-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
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
	public static function get_backfill_args() : array {
		return [
			'post_type' => [ 'post', 'podcast-episode' ],
			'tax_query' => [
				'relation' => 'OR',
				// OpenAir posts.
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'openair',
				],
				// OpenAir podcast episodes.
				[
					'taxonomy' => 'podcast',
					'field'    => 'term_id',
					'terms'    => \CPR\get_podcast_term_ids_by_section( 'openair' ),
				],
			],
		];
	}

	/**
	 * Get the backfill arguments for posts in the OpenAir section.
	 * Used in the Articles component of this page.
	 *
	 * @return array
	 */
	public static function get_openair_posts_backfill_args() : array {
		return [
			'post_type' => [ 'post' ],
			'tax_query' => [
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'openair',
				],
			],
		];
	}

	/**
	 * Get the backfill arguments for podcast episodes in the OpenAir section.
	 * Used in the Podcast Episodes component of this page.
	 *
	 * @return array
	 */
	public static function get_openair_episodes_backfill_args() : array {
		return [
			'post_type' => [ 'podcast-episode' ],
			'tax_query' => [
				[
					'taxonomy' => 'podcast',
					'field'    => 'term_id',
					'terms'    => \CPR\get_podcast_term_ids_by_section( 'openair' ),
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
		$data = (array) get_post_meta( $this->get_post_id(), 'openair', true );
		return [
			( new \CPR\Components\Column_Area() )
				->set_theme( 'threeColumn' )
				->append_children(
					[
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
									'title'        => 'feature',
									'eyebrow'      => 'small',
								]
							),

						/**
						 * Left sidebar with a station playlist.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'left' )
							->append_child(
								/**
								 * Station Playlist.
								 */
								( new \CPR\Components\Audio\Station_Playlist() )
									->set_playlist_item_components( 4 )
							),

						/**
						 * Right sidebar with a concert calendar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->append_child(
								/**
								 * Concert Calendar.
								 */
								( new \CPR\Components\Events\Calendar() )
									->parse_from_fm_data(
										$data['calendar'] ?? [],
										4,
										[
											'post_type'  => 'event',
											'order'      => 'ASC',
											'orderby'    => 'meta_value_num',
											'meta_key'   => 'start_datetime',
											'meta_query' => [
												[
													'key'     => 'end_datetime',
													'value'   => date( 'U' ),
													'compare' => '>=',
												],
											],
											'tax_query'  => [
												[
													'taxonomy' => 'section',
													'field'    => 'slug',
													'terms'    => 'openair',
												],
											],
										]
									)
							),
					]
				),

			/**
			 * Newsletter CTA.
			 */
			( new \CPR\Components\Modules\Newsletter() )
				->merge_config(
					[
						'background_color' => \CPR\get_site_color( 'openair' ),
						'subscribe_group'  => 'spinsider',
						'heading'          => __( 'Sign Up For Spinsider From CPR\'s OpenAir', 'cpr' ),
						'tagline'          => __( 'To sign up to receive our emails, fill in the following field and hit submit. Thanks, and welcome!', 'cpr' ),
					]
				),

			/**
			 * Videos content list.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->merge_config(
					[
						'heading'           => $data['videos']['heading'] ?? '',
						'heading_border'    => true,
						'heading_cta_label' => __( 'All Videos', 'cpr' ),
						'heading_cta_link'  => home_url(), // @todo Update once known.
						'image_size'        => 'feature_item_small',
						'theme'             => 'featureHalf',
						'eyebrow_location'  => 'none',
						'show_excerpt'      => true,
					]
				)
				->add_video_items(
					$data['videos']['content_item_ids'] ?? [],
					2,
					self::get_openair_posts_backfill_args() // @todo Determine actual backfill args.
				),

			/**
			 * Top 30.
			 */
			( new \CPR\Components\Audio\Top_30() )
				->set_from_latest(),

			/**
			 * Podcast episodes content list.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->merge_config(
					[
						'image_size'       => 'grid_item',
						'background_color' => '#f8f9fa',
						'eyebrow_location' => 'top',
					]
				)
				->set_theme( 'gridCentered' )
				->parse_from_fm_data(
					$data['podcast_episodes'] ?? [],
					4,
					self::get_openair_episodes_backfill_args()
				)
				->set_child_themes(
					[
						'content-item' => 'gridPrimary',
						'eyebrow'      => 'small',
						'title'        => 'grid',
					]
				),

			/**
			 * Banner Ad.
			 */
			( new \CPR\Components\Ad() )
				->merge_config(
					[
						'background_color'   => '#f8f9fa',
						'background_padding' => true,
						'width'              => 468,
						'height'             => 60,
					]
				),

			/**
			 * Articles content list.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'split' )
				->merge_config(
					[
						'heading'           => $data['articles']['heading'] ?? '',
						'heading_cta_label' => __( 'All Stories', 'cpr' ),
						'heading_cta_link'  => get_term_link( 'openair', 'section' ),
						'heading_link'      => get_term_link( 'openair', 'section' ),
					]
				)
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'image_size'       => 'feature_item_small',
									'show_excerpt'     => true,
									'eyebrow_location' => 'none',
								]
							)
							->parse_from_ids(
								array_slice( $data['articles']['content_item_ids'] ?? [], 0, 1 ),
								1,
								self::get_openair_posts_backfill_args()
							)
							->set_theme( 'feature' )
							->set_child_themes(
								[
									'content-item' => 'featureHalf',
									'eyebrow'      => 'small',
									'title'        => 'featureSecondary',
								]
							),

						/**
						 * Right sidebar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->append_child(
								/**
								 * Grid of additional items.
								 */
								( new \CPR\Components\Modules\Content_List() )
									->parse_from_ids(
										array_slice( $data['articles']['content_item_ids'] ?? [], 1 ),
										4,
										self::get_openair_posts_backfill_args()
									)
							)
							->set_child_themes(
								[
									'content-list' => 'gridPrimary',
									'content-item' => 'gridPrimary',
									'eyebrow'      => 'small',
									'title'        => 'grid',
								]
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
		$fields['openair'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'OpenAir', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'openair',
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
					'calendar' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Concert Calendar', 'cpr' ),
							'children' => ( new \CPR\Components\Events\Calendar() )->get_fm_fields(),
						]
					),
					'videos' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Videos', 'cpr' ),
							'children' => [
								'heading' => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Heading', 'cpr' ),
										'default_value' => __( 'Watch', 'cpr' ),
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Videos', 'cpr' ),
										'post_limit' => 2,
										'query_args' => [
											'post_type' => 'post',
										],
									]
								),
							],
						]
					),
					'podcast_episodes' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Podcast Episodes', 'cpr' ),
							'children' => [
								'heading' => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Heading', 'cpr' ),
										'default_value' => __( 'Listen', 'cpr' ),
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Podcast Episodes', 'cpr' ),
										'post_limit' => 4,
										'query_args' => self::get_openair_episodes_backfill_args(),
									]
								),
							],
						]
					),
					'articles' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Articles', 'cpr' ),
							'children' => [
								'heading' => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Heading', 'cpr' ),
										'default_value' => __( 'Read', 'cpr' ),
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Articles', 'cpr' ),
										'post_limit' => 5,
										'query_args' => self::get_openair_posts_backfill_args(),
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
