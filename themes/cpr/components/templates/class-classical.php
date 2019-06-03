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
									->set_theme( 'sidebar' )
							),

						/**
						 * Right sidebar with a concert calendar.
						 */
						( new \CPR\Components\Sidebar() )
							->set_theme( 'right' )
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
										4,
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
			 * Newsletter CTA.
			 */
			( new \CPR\Components\Modules\Newsletter() )
				->set_config( 'background_color', \CPR\get_site_color( 'classical' ) ),

			/**
			 * Articles content list.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'split' )
				->merge_config(
					[
						'heading'           => $data['articles']['heading'] ?? __( 'Read', 'cpr' ),
						'heading_cta_label' => __( 'All Stories', 'cpr' ),
						'heading_cta_link'  => get_term_link( 'classical', 'section' ),
						'heading_link'      => get_term_link( 'classical', 'section' ),
					]
				)
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->merge_config(
								[
									'image_size'        => 'feature_item',
									'show_excerpt'      => true,
									'eyebrow_location'  => 'none',
								]
							)
							->parse_from_ids(
								array_slice( $data['articles']['content_item_ids'] ?? [], 0, 1 ),
								1,
								self::get_classical_posts_backfill_args()
							)
							->set_theme( 'feature' )
							->set_child_themes(
								[
									'content-item'  => 'featureSecondary',
									'eyebrow'       => 'small',
									'content-title' => 'featureSecondary',
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
									->set_config( 'image_size', 'grid_item' )
									->parse_from_ids(
										array_slice( $data['articles']['content_item_ids'] ?? [], 1 ),
										4,
										self::get_classical_posts_backfill_args()
									)
							)
							->set_child_themes(
								[
									'content-list'  => 'gridHalf',
									'content-item'  => 'grid',
									'eyebrow'       => 'small',
									'content-title' => 'grid',
								]
							),
					]
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
				->parse_from_fm_data(
					$data['podcast_episodes'] ?? [],
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
						'heading'           => $data['videos']['heading'] ?? __( 'Watch', 'cpr' ),
						// 'heading_cta_label' => __( 'All Videos', 'cpr' ),
						// 'heading_cta_link'  => home_url(), // @todo Update once known.
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
							self::get_classical_posts_backfill_args() // @todo Determine actual backfill args.
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
			 * Hosts.
			 */
			( new \CPR\Components\Modules\Grid_Group() )
				->parse_from_fm_data( (array) $data['hosts'] )
				->children_callback(
					function( $child ) {
						return $child->set_config( 'show_name', $this->wp_post_get_title() );
					}
				),
		];
	}

	/**
	 * Generate a content list of people items from FM data.
	 *
	 * @param array $data People data array.
	 * @return \CPR\Components\Modules\Content_List
	 */
	public function get_people_list( $data ) : \CPR\Components\Modules\Content_List {
		$people_list = ( new \CPR\Components\Modules\Content_List() )
			->merge_config(
				[
					'image_size'        => 'feature_item_small', // @todo change
					'theme'             => 'featureSecondary', // @todo change
				]
			);

		foreach ( ( $data['content_items'] ?? [] ) as $item ) {
			$people_list->append_child(
				( new \CPR\Components\Person_Item() )
					->set_guest_author( get_post( $item['guest_author'] ?? 0 ) )
					->merge_config(
						[
							'subheading' => sprintf(
								/* translators: a show title */
								esc_html__( 'Host, “%s”', 'cpr' ),
								$item['show'] ?? ''
							),
						]
					)
			);
		}

		return $people_list;
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
