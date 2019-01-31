<?php
/**
 * Social Links Item component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Social Links.
 */
class Social_Links_Item extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
  public $name = 'social-links-item';

  /**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {

    return [
      'type' => '',
      'url' => '',
      'display-icon' => true
    ];
	}



}


