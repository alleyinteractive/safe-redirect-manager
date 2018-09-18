<?php
/**
 * Taxonomy base class file
 *
 * @package Cpr
 */

/**
 * Abstract class for taxonomy classes.
 */
abstract class Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = null;

	/**
	 * Object types for this taxonomy.
	 *
	 * @var array
	 */
	public $object_types = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Create the taxonomy.
		add_action( 'init', [ $this, 'create_taxonomy' ] );
	}

	/**
	 * Create the taxonomy.
	 */
	abstract public function create_taxonomy();
}
