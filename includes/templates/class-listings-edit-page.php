<?php
/**
 * Listings edit page template.
 *
 * @template listing_edit_page
 * @description Listing page in edit context.
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listings edit page template class.
 *
 * @class Listings_Edit_Page
 */
class Listings_Edit_Page extends User_Account_Page {

	/**
	 * Template blocks.
	 *
	 * @var array
	 */
	protected static $blocks = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Template arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content' => [
						'blocks' => [
							'listings' => [
								'type'     => 'listings',
								'template' => 'edit',
								'_order'    => 10,
							],
						],
					],
				],
			],
			$args,
			'blocks'
		);

		parent::init( $args );
	}
}
