<?php
/**
 * Listing submit page template.
 *
 * @template listing_submit_page
 * @description Listing submission page.
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing submit page template class.
 *
 * @class Listing_Submit_Page
 */
class Listing_Submit_Page extends Page {

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
					'page_container' => [
						'blocks' => [
							'page_content' => [
								'type'   => 'container',
								'tag'    => 'main',
								'_order'  => 10,

								'blocks' => [
									'page_title' => [
										'type'     => 'element',
										'filepath' => 'page/page-title',
										'_order'    => 5,
									],
								],
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
