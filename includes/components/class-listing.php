<?php
/**
 * Listing component.
 *
 * @package HivePress\Components
 */

namespace HivePress\Components;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing component class.
 *
 * @class Listing
 */
final class Listing {

	/**
	 * Class constructor.
	 */
	public function __construct() {

		// Set vendor.
		add_action( 'save_post_' . 'hp_listing', [ $this, 'set_vendor' ], 10, 2 );

		// Set image.
		add_action( 'add_attachment', [ $this, 'set_image' ] );
		add_action( 'edit_attachment', [ $this, 'set_image' ] );

		// Update status.
		add_action( 'transition_post_status', [ $this, 'update_status' ], 10, 3 );
	}

	/**
	 * Sets vendor.
	 *
	 * @param int     $listing_id Listing ID.
	 * @param WP_Post $listing Listing object.
	 */
	public function set_vendor( $listing_id, $listing ) {

		// Get vendor ID.
		$vendor_id = hp\get_post_id(
			[
				'post_type'   => 'hp_vendor',
				'post_status' => 'any',
				'post__in'    => [ absint( $listing->post_parent ) ],
			]
		);

		if ( 0 === $vendor_id ) {

			// Get user ID.
			$user_id = absint( $listing->post_author );

			// Get vendor ID.
			$vendor_id = hp\get_post_id(
				[
					'post_type'   => 'hp_vendor',
					'post_status' => 'any',
					'author'      => $user_id,
				]
			);

			if ( 0 === $vendor_id ) {

				// Add vendor.
				$vendor_id = wp_insert_post(
					[
						'post_title'   => get_userdata( $user_id )->display_name,
						'post_content' => get_user_meta( $user_id, 'description', true ),
						'post_type'    => 'hp_vendor',
						'post_status'  => 'publish',
						'post_author'  => $user_id,
					]
				);
			}

			// Set vendor ID.
			wp_update_post(
				[
					'ID'          => $listing_id,
					'post_parent' => $vendor_id,
				]
			);
		}
	}

	/**
	 * Sets image.
	 *
	 * @param int $attachment_id Attachment ID.
	 */
	public function set_image( $attachment_id ) {

		// Get listing ID.
		$listing_id = wp_get_post_parent_id( $attachment_id );

		if ( get_post_type( $listing_id ) === 'hp_listing' ) {

			// Get mime type.
			$mime_type = get_post_mime_type( $attachment_id );

			if ( in_array( $mime_type, [ 'image/jpeg', 'image/png' ], true ) ) {

				// Get image IDs.
				$image_ids = wp_list_pluck( get_attached_media( 'image', $listing_id ), 'ID' );

				// Set image.
				if ( ! empty( $image_ids ) ) {
					set_post_thumbnail( $listing_id, reset( $image_ids ) );
				}
			}
		}
	}

	/**
	 * Updates status.
	 *
	 * @param string  $new_status New status.
	 * @param string  $old_status Old status.
	 * @param WP_Post $listing Listing object.
	 */
	public function update_status( $new_status, $old_status, $listing ) {
		if ( 'hp_listing' === $listing->post_type && 'pending' === $old_status ) {

			// Get user.
			$user = get_userdata( $listing->post_author );

			if ( 'publish' === $new_status ) {

				// Send approval email.
				( new Emails\Listing_Approve(
					[
						'recipient' => $user->user_email,
						'tokens'    => [
							'user_name'     => $user->display_name,
							'listing_title' => $listing->post_title,
							'listing_url'   => get_permalink( $listing->ID ),
						],
					]
				) )->send();
			} elseif ( 'trash' === $new_status ) {

				// Send rejection email.
				( new Emails\Listing_Reject(
					[
						'recipient' => $user->user_email,
						'tokens'    => [
							'user_name'     => $user->display_name,
							'listing_title' => $listing->post_title,
						],
					]
				) )->send();
			}
		}
	}
}
