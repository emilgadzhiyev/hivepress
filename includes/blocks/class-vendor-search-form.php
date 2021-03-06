<?php
/**
 * Vendor search form block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Vendor search form block class.
 *
 * @class Vendor_Search_Form
 */
class Vendor_Search_Form extends Form {

	/**
	 * Class initializer.
	 *
	 * @param array $meta Block meta.
	 */
	public static function init( $meta = [] ) {
		$meta = hp\merge_arrays(
			[
				'label' => hivepress()->translator->get_string( 'vendor_search_form' ),
			],
			$meta
		);

		parent::init( $meta );
	}

	/**
	 * Class constructor.
	 *
	 * @param array $args Block arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'form'       => 'vendor_search',

				'attributes' => [
					'class' => [ 'hp-form--wide', 'hp-block' ],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}
