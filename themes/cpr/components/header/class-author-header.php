<?php
/**
 * Author Header component.
 *
 * @package CPR
 */

namespace CPR\Components\Header;

/**
 * Author Header.
 */
class Author_Header extends \WP_Components\Component {

	use \WP_Components\Guest_Author;
	use \WP_Components\Author;
	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
    public $name = 'author-header';
    
    /**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
            'email'       => '',
			'link'        => '',
            'name'        => '',
			'twitter'     => '',
		];
	}

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		$this->set_author( $this->query->get( 'author_name' ) );
		$this->guest_author_has_set( $this->query->get( 'author_name' ) );
		return $this;
	}
}
