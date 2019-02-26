<?php
/**
 * Classical Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

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
	public static function get_classical_posts_backfill_args() {
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
	public static function get_classical_episodes_backfill_args() {
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
			/**
			 * Featured content with a left and right sidebar.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'image_size', 'feature_item' )
				->set_config( 'theme', 'feature' )
				->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
				->append_children(
					[
						/**
						 * Left sidebar with a station playlist.
						 */
						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'left' )
							->append_child(
								/**
								 * Station Playlist.
								 */
								( new \CPR\Component\Audio\Station_Playlist() )
									->set_playlist_item_components( 4 )
							),

						/**
						 * Right sidebar with a concert calendar.
						 */
						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'right' )
							->append_child(
								/**
								 * Concert Calendar.
								 */
								( new \CPR\Component\Events\Calendar() )
									->parse_from_fm_data(
										$data['calendar'] ?? [],
										4,
										[
											'post_type' => 'event',
											'tax_query' => [
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
			( new \CPR\Component\Modules\Newsletter() )
				->set_config( 'background_color', \CPR\get_site_color( 'classical' ) ),

			/**
			 * Articles content list.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->merge_config(
					[
						'heading'           => $data['articles']['heading'] ?? '',
						'heading_border'    => true,
						'heading_cta_label' => __( 'All Stories', 'cpr' ),
						'heading_cta_link'  => get_term_link( 'classical', 'section' ),
						'heading_link'      => get_term_link( 'classical', 'section' ),
						'image_size'        => 'feature_item_small',
						'show_excerpt'      => true,
						'theme'             => 'featureTerm', // @todo May need to be featureHalf, or other.
						'eyebrow_location'  => 'none',
					]
				)
				->parse_from_ids(
					array_slice( $data['articles']['content_item_ids'] ?? [], 0, 1 ),
					1,
					self::get_classical_posts_backfill_args()
				)
				->append_child(
					/**
					 * Right sidebar.
					 */
					( new \CPR\Component\Sidebar() )
						->set_config( 'position', 'right' )
						->append_child(
							/**
							 * Grid of additional items.
							 */
							( new \CPR\Component\Modules\Content_List() )
							->merge_config(
									[
										'theme'             => 'grid',
										'eyebrow_location'  => 'none',
									]
								)
								->parse_from_ids(
									array_slice( $data['articles']['content_item_ids'] ?? [], 1 ),
									4,
									self::get_classical_posts_backfill_args()
								)
						)
				),

			/**
			 * Podcast episodes content list.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->merge_config(
					[
						'image_size'       => 'grid_item',
						'theme'            => 'grid',
						'background_color' => '#f8f9fa',
						'eyebrow_location' => 'top',
					]
				)
				->parse_from_fm_data(
					$data['podcast_episodes'] ?? [],
					4,
					self::get_classical_episodes_backfill_args()
				),

			/**
			 * Videos content list.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->merge_config(
					[
						'heading'           => $data['videos']['heading'] ?? '',
						'heading_border'    => true,
						'heading_cta_label' => __( 'All Videos', 'cpr' ),
						'heading_cta_link'  => home_url(), // @todo Update.
						'image_size'        => 'feature_item_small',
						'theme'             => 'featureHalf',
						'eyebrow_location'  => 'none',
						'show_excerpt'      => true,
					]
				)
				->add_video_items(
					$data['videos']['content_item_ids'] ?? [],
					2,
					self::get_classical_posts_backfill_args() // @todo Determine actual backfill args.
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
							'children' => ( new \CPR\Component\Events\Calendar() )->get_fm_fields(),
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
				],
			]
		);
		return $fields;
	}
}
