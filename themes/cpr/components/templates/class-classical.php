<?php
/**
 * Classical Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Classical template.
 */
class Classical extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'classical-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body = ( new \WP_Components\Body() )
			->set_config( 'body_classes', 'classical' );
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
				// Classical posts.
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'classical',
				],
				// Classical podcast episodes.
				[
					'taxonomy' => 'podcast',
					'field'    => 'term_id',
					'terms'    => \CPR\get_podcast_term_ids_by_section( 'classical' ),
				],
			],
		];
	}

	/**
	 * Get the backfill arguments for posts in the classical section.
	 * Used in the Articles component of this page.
	 *
	 * @return array
	 */
	public static function get_classical_posts_backfill_args() : array {
		return [
			'post_type' => [ 'post' ],
			'tax_query' => [
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'classical',
				],
			],
		];
	}

	/**
	 * Get the backfill arguments for podcast episodes in the classical section.
	 * Used in the Podcast Episodes component of this page.
	 *
	 * @return array
	 */
	public static function get_classical_episodes_backfill_args() : array {
		return [
			'post_type' => [ 'podcast-episode' ],
			'tax_query' => [
				[
					'taxonomy' => 'podcast',
					'field'    => 'term_id',
					'terms'    => \CPR\get_podcast_term_ids_by_section( 'classical' ),
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
		$data = (array) get_post_meta( $this->get_post_id(), 'classical', true );
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
							->parse_from_fm_data(
								$data['featured_content'] ?? [],
								1,
								self::get_classical_posts_backfill_args()
							)
							->set_theme( 'feature' )
							->set_child_themes(
								[
									'content-item'  => 'featurePrimary',
									'content-title' => 'feature',
									'eyebrow'       => 'small',
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
								( new \CPR\Components\Audio\Live_Stream() )
									->set_source( 'classical' )
									->set_config( 'count', 4 )
									->set_config( 'title_link', '/classical/playlist/' )
									->set_theme( 'sidebar' )
							),

						/**
						 * Right sidebar with a concert calendar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
							->set_config( 'has_ad', true )
							->append_child(
								( new \CPR\Components\Advertising\Ad_Unit() )
										->configure_ad_slot( 'CPR3-Combined-300x250' )
							)
							->append_child(

								/**
								 * Concert calendar via a content list with a
								 * river of events.
								 */
								( new \CPR\Components\Widgets\Content_List() )
									->set_config( 'header_text', $data['calendar']['heading'] ?? __( 'Concert Calendar', 'cpr' ) )
									->set_config( 'header_link', $data['calendar']['heading_link'] ?? site_url( 'classical/calendar/' ) )
									->parse_from_post_ids(
										$data['calendar']['event_ids'] ?? [],
										2,
										[
											'post_type'  => 'tribe_events',
											'tax_query'  => [
												[
													'taxonomy' => 'section',
													'field'    => 'slug',
													'terms'    => 'classical',
												],
											],
										]
									)
							),
					]
				),

			/**
			 * Articles content list.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->merge_config(
					[
						'heading'           => $data['articles']['heading'] ?? __( 'Read', 'cpr' ),
						'heading_cta_label' => __( 'All Stories', 'cpr' ),
						'heading_cta_link'  => get_term_link( 'classical', 'section' ),
						'heading_link'      => get_term_link( 'classical', 'section' ),
					]
				)
				->append_child(
					( new \CPR\Components\Modules\Content_List() )
						->merge_config(
							[
								'image_size'   => 'feature_item_small',
								'show_excerpt' => true,
								'theme'        => 'featureHalf',
							]
						)
						->parse_from_ids(
							array_slice( $data['articles']['content_item_ids'] ?? [], 0, 2 ),
							2,
							self::get_classical_posts_backfill_args()
						)
						->set_theme( 'gridHalf' )
						->set_child_themes(
							[
								'content-item'  => 'featureSecondary',
								'content-title' => 'featureSecondary',
								'eyebrow'       => 'small',
							]
						)
				),

			/**
			 * Podcast episodes content list.
			 */
			( new \CPR\Components\Modules\Content_List() )
				->merge_config(
					[
						'background_color' => '#f8f9fa',
						'eyebrow_location' => 'top',
						'heading'          => __( 'Listen', 'cpr' ),
						'image_size'       => 'grid_item',
					]
				)
				->set_theme( 'gridCentered' )
				->parse_from_ids(
					$data['podcast_episodes']['content_item_ids'] ?? [],
					4,
					self::get_classical_episodes_backfill_args()
				)
				->set_child_themes(
					[
						'content-item'  => 'grid',
						'eyebrow'       => 'small',
						'content-title' => 'grid',
					]
				),

			/**
			 * Videos content list.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->merge_config(
					[
						'heading' => $data['videos']['heading'] ?? __( 'Watch', 'cpr' ),
						'heading_cta_label' => $data['videos']['heading_cta_label'] ?? __( 'More Videos', 'cpr' ),
						'heading_cta_link'  => $data['videos']['heading_cta_link'] ?? 'https://www.youtube.com/user/ColoradoPublicRadio',
					]
				)
				->append_child(
					( new \CPR\Components\Modules\Content_List() )
						->merge_config(
							[
								'image_size'   => 'feature_item_small',
								'show_excerpt' => true,
								'theme'        => 'featureHalf',
							]
						)
						->add_video_items(
							$data['videos']['content_item_ids'] ?? [],
							2,
							self::get_classical_posts_backfill_args()
						)
						->set_theme( 'gridHalf' )
						->set_child_themes(
							[
								'content-item'  => 'featureSecondary',
								'content-title' => 'featureSecondary',
							]
						)
				),

			/**
			 * Hosts.
			 */
			( new \CPR\Components\Modules\Grid_Group() )
				->parse_from_fm_data( (array) $data['hosts'] ?? [] )
				->children_callback(
					function( $child ) {
						return $child->set_config( 'show_name', $this->wp_post_get_title() );
					}
				),
		];
	}

	/**
	 * Add additional FM fields to a landing page.
	 *
	 * @param array $fields FM fields.
	 * @return array
	 */
	public static function landing_page_fields( $fields ) : array {
		$fields['classical'] = new \Fieldmanager_Group(
			[
				'label'      => __( 'Classical', 'cpr' ),
				'tabbed'     => 'vertical',
				'display_if' => [
					'src'   => 'landing_page_type',
					'value' => 'classical',
				],
				'children' => [
					'featured_content' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Featured Content', 'cpr' ),
							'children' => [
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Featured Story', 'cpr' ),
										'post_limit' => 1,
										'query_args' => self::get_backfill_args(),
									]
								),
							],
						]
					),
					'calendar' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Concert Calendar', 'cpr' ),
							'children' => [
								'heading'      => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Heading', 'cpr' ),
										'default_value' => 'Upcoming Events',
									]
								),
								'heading_link' => new \Fieldmanager_Textfield(
									[
										'label'         => __( 'Heading Link', 'cpr' ),
										'default_value' => '/classical/calendar/',

									]
								),
								'event_ids'    => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Events', 'cpr' ),
										'post_limit' => 4,
										'query_args' => [
											'post_type' => [ 'tribe_events', 'post' ],
										],
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
										'query_args' => self::get_classical_posts_backfill_args(),
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
										'query_args' => self::get_classical_episodes_backfill_args(),
									]
								),
							],
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
								'heading_cta_label' => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Call to Action', 'cpr' ),
										'default_value' => __( 'More Videos', 'cpr' ),
									]
								),
								'heading_cta_link' => new \Fieldmanager_Link(
									[
										'label'         => __( 'Call to Action Link', 'cpr' ),
										'default_value' => 'https://www.youtube.com/user/ColoradoPublicRadio',
									]
								),
								'content_item_ids' => new \Fieldmanager_Zone_Field(
									[
										'label'      => __( 'Videos', 'cpr' ),
										'post_limit' => 2,
										'query_args' => [
											'post_type' => 'post',
											'tax_query' => [
												[
													'taxonomy' => 'section',
													'field'    => 'slug',
													'terms'    => 'classical',
												],
											],
											'meta_query' => [
												[
													'key'     => 'featured_media_type',
													'compare' => '=',
													'value'   => 'video',
												],
												[
													'key'     => 'video_url',
													'compare' => 'EXISTS',
												],
											],
										],
									]
								),
							],
						]
					),
					'hosts' => new \Fieldmanager_Group(
						[
							'label'    => __( 'Hosts', 'cpr' ),
							'children' => \CPR\Components\Modules\Grid_Group::get_fm_fields(),
						]
					),
				],
			]
		);
		return $fields;
	}
}
