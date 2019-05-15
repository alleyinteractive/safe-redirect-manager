<?php
/**
 * Grid_Group_Page Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Grid Group template.
 */
class Grid_Group_Page extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'grid-group-page-template';

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
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		$grid_groups = (array) get_post_meta( $this->get_post_id(), 'grid-group', true );
		return \CPR\Components\Modules\Grid_Group::get_grid_group_components( $grid_groups['groups'] ?? [] );
	}

	/**
	 * Register the page template for this layout.
	 */
	public static function register_page_template() {
		if ( ! function_exists( '\Alleypack\Page_Templates\register' ) ) {
			return;
		}

		\Alleypack\Page_Templates\register(
			__( 'Grid Group', 'cpr' ),
			'grid-group',
			[
				'groups' => new \Fieldmanager_Group(
					[
						'label'          => __( 'Groups', 'cpr' ),
						// Translators; $s - Group name.
						'label_macro'    => [ __( 'Group: %s', 'cpr' ), 'name' ],
						'add_more_label' => __( 'Add Group', 'cpr' ),
						'limit'          => 0,
						'collapsed'      => true,
						'collapsible'    => true,
						'sortable'       => true,
						'children'       => \CPR\Components\Modules\Grid_Group::get_fm_fields(),
					]
				),
			]
		);
	}
}
