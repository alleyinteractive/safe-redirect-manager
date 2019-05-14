<?php
/**
 * Create a Field Manager settings metabox using page templates.
 *
 * @package CPR
 */

namespace CPR;

/**
 *
 */
class FM_Page_Templates {

	use \Alleypack\Singleton;

	/**
	 * Templates.
	 *
	 * @var array
	 */
	public $templates = [];

	/**
	 * Singleton Initialization.
	 */
	public function setup() {
		add_action( 'fm_post_page', [ $this, 'add_meta_box' ] );
		add_action( 'theme_templates', [ $this, 'theme_templates' ] );
	}

	/**
	 * Add a template.
	 *
	 * @param [type] $args [description]
	 */
	public function add_template( $args ) {

		$args = wp_parse_args(
			$args,
			[
				'fields' => [],
				'name'   => '',
				'slug'   => $args['name'] ?? '',
				'tabbed' => '',
			]
		);

		$args['slug']  = sanitize_title( $args['slug'] );
		$args['group'] = new \Fieldmanager_Group(
			[
				'name'           => "page-template-{$args['slug']}",
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'tabbed'         => $args['tabbed'],
				'children'       => $args['fields'],
			]
		);

		// Add this template.
		$this->templates[] = $args;
	}

	public function get_options() {
		$options = [];
		foreach ( $this->templates as $template ) {
			$options[ $template['slug'] ] = $template['name'];
		}
		return $options;
	}

	/**
	 * Filter the theme templates to include the registered templates.
	 *
	 * @param array $templates Page templates.
	 * @return array
	 */
	public function theme_templates( $templates ) {
		return array_merge(
			$templates,
			$this->get_options()
		);
	}

	public function add_meta_box() {

		$children = [
			'_wp_page_template' => new \Fieldmanager_Select(
				[
					'label'       => __( 'Template', 'cpr' ),
					'options'     => $this->get_options(),
					'first_empty' => true,
				]
			),
		];

		foreach ( $this->templates as $template ) {
			$children[ $template['slug'] ] = $template['fields'];
		}

		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'fm-page-templates',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => $children,
			]
		);
		$fm->add_meta_box( __( 'Templates', 'cpr' ), [ 'page' ], 'normal', 'high' );
	}
}

add_action(
	'init',
	function() {
		FM_Page_Templates::instance();
	}
);
