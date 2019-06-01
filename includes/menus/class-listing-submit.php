<?php
/**
 * Listing submit menu.
 *
 * @package HivePress\Menus
 */

namespace HivePress\Menus;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing submit menu class.
 *
 * @class Listing_Submit
 */
class Listing_Submit extends Menu {

	/**
	 * Menu name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Chained property.
	 *
	 * @var bool
	 */
	protected static $chained = false;

	/**
	 * Menu items.
	 *
	 * @var array
	 */
	protected static $items = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Menu arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'chained' => true,
				'items'   => [
					'submit_listing'  => [
						'route' => 'listing/submit_listing',
						'order' => 10,
					],

					'submit_category' => [
						'route' => 'listing/submit_category',
						'order' => 20,
					],

					'submit_details'  => [
						'route' => 'listing/submit_details',
						'order' => 30,
					],

					'submit_complete' => [
						'route' => 'listing/submit_complete',
						'order' => 40,
					],
				],
			],
			$args
		);

		parent::init( $args );
	}
}
