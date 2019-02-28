<?php
/**
 * Listing search form block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing search form block class.
 *
 * @class Listing_Search_Form
 */
class Listing_Search_Form extends Block {

	/**
	 * Class constructor.
	 *
	 * @param array $args Block arguments.
	 */
	public function __construct( $args = [] ) {
		$args = array_replace_recursive(
			[
				'title' => esc_html__( 'Listing Search Form', 'hivepress' ),
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		// todo.
		$form = new \HivePress\Forms\Listing_Update();
		$form->set_values(
			[
				'images' => get_posts(
					[
						'post_type'      => 'attachment',
						'post_parent'    => 163,
						'fields'         => 'ids',
						'posts_per_page' => -1,
					]
				),
			]
		);

		return $form->render();
	}
}