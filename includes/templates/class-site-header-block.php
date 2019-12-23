<?php
/**
 * Site header block template.
 *
 * @template site_header_block
 * @description Site header block.
 * @package HivePress\Templates
 */

namespace HivePress\Templates;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Site header block template class.
 *
 * @class Site_Header_Block
 */
class Site_Header_Block extends Template {

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
					'main_menu' => [
						'type'       => 'container',
						'_order'      => 10,

						'attributes' => [
							'class' => [ 'hp-menu', 'hp-menu--main' ],
						],

						'blocks'     => [
							'user_account_link'   => [
								'type'     => 'element',
								'filepath' => 'user/login/user-login-link',
								'_order'    => 10,
							],

							'listing_submit_link' => [
								'type'     => 'element',
								'filepath' => 'listing/submit/listing-submit-link',
								'_order'    => 20,
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
