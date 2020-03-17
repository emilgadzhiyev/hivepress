<?php
/**
 * Listing edit page template.
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
 * Listing edit page template class.
 *
 * @class Listing_Edit_Page
 */
class Listing_Edit_Page extends Listing_Manage_Page {

	/**
	 * Class constructor.
	 *
	 * @param array $args Template arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_trees(
			[
				'blocks' => [
					'page_content'              => [
						'blocks' => [
							'listing_update_form' => [
								'type'   => 'form',
								'form'   => 'listing_update',
								'_order' => 10,

								// @deprecated since version 1.3.2.
								'footer' => [
									'form_actions' => [
										'type'       => 'container',
										'blocks'     => [],
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-form__actions' ],
										],
									],
								],
							],
						],
					],

					'listing_actions_secondary' => [
						'blocks' => [
							'listing_delete_modal' => [
								'type'   => 'modal',
								'title'  => hivepress()->translator->get_string( 'delete_listing' ),
								'_order' => 5,

								'blocks' => [
									'listing_delete_form' => [
										'type'       => 'form',
										'form'       => 'listing_delete',
										'_order'     => 10,

										'attributes' => [
											'class' => [ 'hp-form--narrow' ],
										],
									],
								],
							],

							'listing_delete_link'  => [
								'type'   => 'part',
								'path'   => 'listing/edit/page/listing-delete-link',
								'_order' => 1000,
							],
						],
					],
				],
			],
			$args
		);

		parent::__construct( $args );
	}
}
