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
