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
	public function get_backfill_args() {
		return [
			'tax_query' => [
				[
					'taxonomy' => 'section',
					'field'    => 'slug',
					'terms'    => 'news',
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
			 * Featured content with a left and right sidebar.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'theme', 'feature' )
				->parse_from_fm_data( $data['featured_content'] ?? [], 1 )
				->append_children(
					[
						/**
						 * Right sidebar with an ad.
						 */
						( new \CPR\Component\Sidebar() )
							->set_config( 'position', 'right' )
							->append_child( new \CPR\Component\Ad() ),
					]
				),

			/**
			 * Highlighted Content.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->parse_from_fm_data(
					$data['highlighted_content'] ?? [],
					4,
					$this->get_backfill_args()
				)
				->set_config( 'theme', 'grid' )
				->set_config( 'call_to_action_label', __( 'All Stories', 'cpr' ) )
				->set_config( 'call_to_action_link', home_url( '/all/' ) ),

			/**
			 * Newsletter CTA.
			 */
			new \CPR\Component\Modules\Newsletter(),

			/**
			 * "Featured Topic"
			 */
			( new \CPR\Component\Modules\Content_List() )
				->parse_from_fm_data(
					[],
					1,
					$this->get_backfill_args()
				)
				->set_config( 'heading', __( 'Election 2018', 'cpr' ) )
				->set_config( 'heading_link', home_url( '/election-2018/' ) )
				->set_config( 'eyebrow_label', __( 'Featured Topic', 'cpr' ) )
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
								->parse_from_fm_data(
									[],
									1,
									$this->get_backfill_args()
								)
						)
				),

			/**
			 * Banner Ad.
			 */
			new \CPR\Component\Ad(),

			/**
			 * "More Stories" river.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'layout', 'river' )
				->set_config( 'heading', __( 'More Stories', 'cpr' ) )
				->parse_from_fm_data(
					[],
					10,
					$this->get_backfill_args()
				)
				->append_child(
					/**
					 * Pagination.
					 *
					 * @todo Implement.
					 */

					/**
					 * Right Sidebar.
					 */
					( new \CPR\Component\Sidebar() )
						->set_config( 'position', 'right' )
						->append_children(
							[
								/**
								 * River of content "Across Colorado"
								 */
								( new \CPR\Component\Modules\Content_List() )
									->set_config( 'layout', 'river' )
									->set_config( 'heading', __( 'Across Colorado', 'cpr' ) )
									->parse_from_fm_data(
										[],
										4,
										[] // TODO: Determine what kind of content this actually is.
									),

								/**
								 * Colorado Wonders question form.
								 */
								// new \CPR\Component\Colorado_Wonders(), // phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar

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
										'label' => __( 'Featured Story', 'cpr' ),
										'post_limit'     => 1,
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
				],
			]
		);
		return $fields;
	}
}
