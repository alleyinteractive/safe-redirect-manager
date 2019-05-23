<?php
/**
 * Logo component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Logo.
 */
class Logo extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'logo';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'type' => 'main',
			'link' => '/',
		];
	}

	/**
	 * Type config value has changed.
	 *
	 * @return self
	 */
	public function type_config_has_set() : self {
		switch ( $this->get_config( 'type' ) ) {
			case 'main':
				$this->set_config( 'link', '/' );
				break;
			case 'news':
				$this->set_config( 'link', '/news/' );
				break;
			case 'classical':
				$this->set_config( 'link', '/classical/' );
				break;
			case 'indie':
				$this->set_config( 'link', '/indie/' );
				break;
		}
		return $this;
	}
}
