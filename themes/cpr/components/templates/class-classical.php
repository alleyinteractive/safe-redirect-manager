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
									->set_playlist_item_components( 4, 'classical' )
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
						'heading'           => $data['articles']['heading'] ?? '',
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
									'image_size'        => 'feature_item_small',
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
										self::get_classical_posts_backfill_args()
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
					self::get_classical_episodes_backfill_args()
				)
				->set_child_themes(
					[
						'content-item' => 'gridPrimary',
						'eyebrow'      => 'small',
						'title'        => 'grid',
					]
				),

			/**
			 * Videos content list.
			 */
			( new \CPR\Components\Column_Area() )
				->merge_config(
					[
						'heading'           => $data['videos']['heading'] ?? '',
						'heading_cta_label' => __( 'All Videos', 'cpr' ),
						'heading_cta_link'  => home_url(), // @todo Update once known.
						
					]
				)
				->append_child(
					( new \CPR\Components\Modules\Content_List() )
						->merge_config(
							[
								'image_size'        => 'feature_item_small',
								'theme'             => 'featureHalf',
								'show_excerpt'      => true,
							]
						)
						->add_video_items(
							$data['videos']['content_item_ids'] ?? [],
							2,
							self::get_classical_posts_backfill_args() // @todo Determine actual backfill args.
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
			 * People list.
			 */
			( new \CPR\Components\Column_Area() )
				->merge_config(
					[
						'heading'           => $data['people']['heading'] ?? '',
						'heading_cta_label' => get_the_title( $data['people']['heading_cta_id'] ?? 0 ),
						'heading_cta_link'  => get_permalink( $data['people']['heading_cta_id'] ?? 0 ),
						
					]
				)
				->append_child(
					$this->get_people_list( $data['people'] ?? [] )
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
					'theme'             => 'featureHalf', // @todo change
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
					'people' => new \Fieldmanager_Group(
						[
							'label'    => __( 'People', 'cpr' ),
							'children' => [
								'heading' => new \Fieldmanager_TextField(
									[
										'label'         => __( 'Heading', 'cpr' ),
										'default_value' => __( 'Hosts', 'cpr' ),
									]
								),
								'heading_cta_id' => new \Fieldmanager_Autocomplete(
									[
										'label'       => __( 'Call to Action button', 'cpr' ),
										'description' => __( 'Select the page to link to in the CTA.', 'cpr' ),
										'datasource'  => new \Fieldmanager_Datasource_Post(
											[
												'query_args' => [
													'post_type' => 'page',
												],
											]
										),
									]
								),
								'content_items' => new \Fieldmanager_Group(
									[
										'add_more_label' => __( 'Add a host', 'cpr' ),
										'label'          => __( 'Host', 'cpr' ),
										'label_macro'    => [ 'Host: %s', 'guest_author' ],
										'extra_elements' => 0,
										'limit'          => 0,
										'collapsed'      => true,
										'sortable'       => true,
										'children'       => [
											'guest_author' => new \Fieldmanager_Autocomplete(
												[
													'label'       => __( 'Host', 'cpr' ),
													'datasource'  => new \Fieldmanager_Datasource_Post(
														[
															'query_args' => [
																'post_type'  => 'guest-author',
																'meta_query' => [
																	[
																		'key'     => 'type',
																		'value'   => 'host',
																		'compare' => '=',
																	],
																],
															],
														]
													),
												]
											),
											'show' => new \Fieldmanager_TextField(
												[
													'label' => __( 'Show Title', 'cpr' ),
												]
											),
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
