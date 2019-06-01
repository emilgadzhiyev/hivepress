<?php
/**
 * Page template.
 *
 * @package HivePress\Configs\Templates
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'blocks' => [
		'site_header'    => [
			'type'     => 'element',
			'filepath' => 'page/header',
			'order'    => 10,
		],

		'page_container' => [
			'type'   => 'page_container',
			'order'  => 20,

			'blocks' => [],
		],

		'site_footer'    => [
			'type'     => 'element',
			'filepath' => 'page/footer',
			'order'    => 30,
		],
	],
];
