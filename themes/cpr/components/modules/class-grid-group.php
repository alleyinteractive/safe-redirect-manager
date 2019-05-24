<?php
/**
 * Grid Group module.
 *
 * @package CPR
 */

namespace CPR\Components\Modules;

/**
 * Grid group.
 */
class Grid_Group extends \WP_Components\Component {

	use \Alleypack\FM_Module;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'grid-group';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'call_to_action_label' => '',
			'call_to_action_link'  => '',
		];
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public static function get_fm_fields() : array {
		return [
			'name'                  => new \Fieldmanager_TextField( __( 'Group Name', 'cpr' ) ),
			'enable_call_to_action' => new \Fieldmanager_Checkbox( __( 'Display Call To Action?', 'cpr' ) ),
			'call_to_action_label'  => new \Fieldmanager_TextField(
				[
					'label'      => __( 'Call to Action Label', 'cpr' ),
					'display_if' => [
						'src'   => 'enable_call_to_action',
						'value' => true,
					],
				]
			),
			'call_to_action_link'   => new \Fieldmanager_TextField(
				[
					'label'      => __( 'Call to Action Link', 'cpr' ),
					'display_if' => [
						'src'   => 'enable_call_to_action',
						'value' => true,
					],
				]
			),
			'post_ids'              => new \Fieldmanager_Zone_Field(
				[
					'description' => __( 'Select items for this group.', 'cpr' ),
					'query_args'  => [
						'post_type' => \CPR\get_curatable_post_types(),
					],
				]
			),
		];
	}

	/**
	 * Get an array of grid group components using an array of group fm fields.
	 *
	 * @param array $fm_groups FM groups data.
	 * @return array
	 */
	public static function get_grid_group_components( array $fm_groups ) : array {
		return array_filter( array_map(
			function( $fm_group ) {
				return ( new self() )
					->parse_from_fm_data( $fm_group );
			},
			$fm_groups ?? []
		) );
	}

	/**
	 * Parse this grid group from FM data.
	 *
	 * @param array $fm_data Array of FM data.
	 * @return self
	 */
	public function parse_from_fm_data( array $fm_data ) : self {

		// Validate some data.
		$fm_data = wp_parse_args(
			$fm_data,
			[
				'name'     => '',
				'post_ids' => '',
			]
		);

		// Set the config values.
		$this->merge_config(
			[
				'name'                 => $fm_data['name'],
				'call_to_action_label' => $fm_data['call_to_action_label'] ?? '',
				'call_to_action_link'  => $fm_data['call_to_action_link'] ?? '',
			]
		);

		// Append the Grid_Group_items as children.
		$this->append_children(
			array_map(
				function( $post_id ) {
					return ( new Grid_Group_Item() )->set_post( $post_id );
				},
				(array) $fm_data['post_ids']
			)
		);

		return $this;
	}
}
