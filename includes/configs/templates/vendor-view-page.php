<?php
/**
 * Vendor view page template.
 *
 * @package HivePress\Configs\Templates
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'blocks' => [
		'container' => [
			'type'       => 'container',
			'order'      => 10,

			'attributes' => [
				'class' => [ 'hp-vendor', 'hp-vendor--view-page' ],
			],

			'blocks'     => [
				'columns' => [
					'type'       => 'container',
					'order'      => 10,

					'attributes' => [
						'class' => [ 'hp-row' ],
					],

					'blocks'     => [
						'sidebar' => [
							'type'       => 'container',
							'tag'        => 'aside',
							'order'      => 10,

							'attributes' => [
								'class'          => [ 'hp-vendor__sidebar', 'hp-col-sm-4', 'hp-col-xs-12' ],
								'data-component' => 'sticky',
							],

							'blocks'     => [
								'image'           => [
									'type'      => 'element',
									'file_path' => 'vendor/view/page/image',
									'order'     => 10,
								],

								'name'            => [
									'type'      => 'element',
									'file_path' => 'vendor/view/page/name',
									'order'     => 20,
								],

								'details_primary' => [
									'type'       => 'container',
									'order'      => 30,

									'attributes' => [
										'class' => [ 'hp-vendor__details', 'hp-vendor__details--primary' ],
									],

									'blocks'     => [
										'date' => [
											'type'      => 'element',
											'file_path' => 'vendor/view/date',
											'order'     => 10,
										],
									],
								],
							],
						],

						'content' => [
							'type'       => 'container',
							'tag'        => 'main',
							'order'      => 20,

							'attributes' => [
								'class' => [ 'hp-vendor__content', 'hp-col-sm-8', 'hp-col-xs-12' ],
							],

							'blocks'     => [
								'listings'   => [
									'type'    => 'listings',
									'columns' => 2,
									'order'   => 10,
								],

								'pagination' => [
									'type'      => 'element',
									'file_path' => 'pagination',
									'order'     => 20,
								],
							],
						],
					],
				],
			],
		],
	],
];
