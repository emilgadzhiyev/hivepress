<?php
/**
 * User controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Models as Models;
use HivePress\Forms as Forms;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * User controller class.
 *
 * @class User
 */
class User extends Controller {

	/**
	 * Class constructor.
	 *
	 * @param array $args Controller arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp_merge_arrays(
			$args,
			[
				'routes' => [
					[
						'path'      => '/users',
						'rest'      => true,
						'endpoints' => [
							[
								'methods' => 'POST',
								'action'  => 'register_user',
							],

							[
								'path'    => '/login',
								'methods' => 'POST',
								'action'  => 'login_user',
							],

							[
								'path'    => '/request-password',
								'methods' => 'POST',
								'action'  => 'request_password',
							],

							[
								'path'    => '/reset-password',
								'methods' => 'POST',
								'action'  => 'reset_password',
							],

							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'POST',
								'action'  => 'update_user',
							],

							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'DELETE',
								'action'  => 'delete_user',
							],
						],
					],

					[
						'title'  => esc_html__( 'Sign In', 'hivepress' ),
						'path'   => '/account/login',
						'action' => 'render_login_page',
					],

					[
						'title'  => esc_html__( 'Reset Password', 'hivepress' ),
						'path'   => '/account/reset-password',
						'action' => 'render_password_page',
					],

					[
						'title'  => esc_html__( 'My Settings', 'hivepress' ),
						'path'   => '/account/settings',
						'action' => 'render_settings_page',
					],
				],
			]
		);

		parent::__construct( $args );
	}

	/**
	 * Registers user.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function register_user( $request ) {

		// Check permissions.
		if ( is_user_logged_in() && ! current_user_can( 'create_users' ) ) {
			return hp_rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\User_Register();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp_rest_error( 400, $form->get_errors() );
		}

		// Check username.
		if ( $form->get_value( 'username' ) ) {
			if ( sanitize_user( $form->get_value( 'username' ), true ) !== $form->get_value( 'username' ) ) {
				return hp_rest_error( 400, esc_html__( 'Username contains invalid characters', 'hivepress' ) );
			} elseif ( username_exists( $form->get_value( 'username' ) ) ) {
				return hp_rest_error( 400, esc_html__( 'This username is already in use', 'hivepress' ) );
			}
		}

		// Check email.
		if ( email_exists( $form->get_value( 'email' ) ) ) {
			return hp_rest_error( 400, esc_html__( 'This email is already registered', 'hivepress' ) );
		}

		// Get username.
		list($username, $domain) = explode( '@', $form->get_value( 'email' ) );

		if ( $form->get_value( 'username' ) ) {
			$username = $form->get_value( 'username' );
		} else {
			$username = sanitize_user( $username, true );

			if ( '' === $username ) {
				$username = 'user';
			}

			while ( username_exists( $username ) ) {
				$username .= wp_rand( 1, 9 );
			}
		}

		// Register user.
		$user = new Models\User();

		$user->fill( array_merge( $form->get_values(), [ 'username' => $username ] ) );

		if ( ! $user->save() ) {
			return hp_rest_error( 400, esc_html__( 'Error registering user', 'hivepress' ) );
		}

		// Hide admin bar.
		update_user_meta( $user->get_id(), 'show_admin_bar_front', 'false' );

		// Send emails.
		wp_new_user_notification( $user->get_id() );

		// todo send email.
		// Authenticate user.
		if ( ! is_user_logged_in() ) {
			wp_set_auth_cookie( $user->get_id(), true );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $user->get_id(),
				],
			],
			201
		);
	}

	/**
	 * Logins user.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function login_user( $request ) {

		// Check permissions.
		if ( is_user_logged_in() && ! current_user_can( 'edit_users' ) ) {
			return hp_rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\User_Login();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp_rest_error( 400, $form->get_errors() );
		}

		// Get user.
		$user = false;

		if ( is_email( $form->get_value( 'username_or_email' ) ) ) {
			$user = get_user_by( 'email', $form->get_value( 'username_or_email' ) );
		} else {
			$user = get_user_by( 'login', $form->get_value( 'username_or_email' ) );
		}

		if ( false === $user ) {
			return hp_rest_error( 401, esc_html__( 'Username or password is incorrect', 'hivepress' ) );
		}

		// Check password.
		if ( ! wp_check_password( $form->get_value( 'password' ), $user->user_pass, $user->ID ) ) {
			return hp_rest_error( 401, esc_html__( 'Username or password is incorrect', 'hivepress' ) );
		}

		// Authenticate user.
		if ( ! is_user_logged_in() ) {
			wp_set_auth_cookie( $user->ID, true );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $user->ID,
				],
			],
			200
		);
	}

	/**
	 * Requests password.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function request_password( $request ) {

		// Check permissions.
		if ( is_user_logged_in() && ! current_user_can( 'edit_users' ) ) {
			return hp_rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\User_Password_Request();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp_rest_error( 400, $form->get_errors() );
		}

		// Get user.
		$user = false;

		if ( is_email( $form->get_value( 'username_or_email' ) ) ) {
			$user = get_user_by( 'email', $form->get_value( 'username_or_email' ) );
		} else {
			$user = get_user_by( 'login', $form->get_value( 'username_or_email' ) );
		}

		if ( false === $user ) {
			if ( is_email( $form->get_value( 'username_or_email' ) ) ) {
				return hp_rest_error( 404, esc_html__( "User with this email doesn't exist", 'hivepress' ) );
			} else {
				return hp_rest_error( 404, esc_html__( "User with this username doesn't exist", 'hivepress' ) );
			}
		}

		// todo send email.
		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $user->ID,
				],
			],
			200
		);
	}

	/**
	 * Resets password.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function reset_password( $request ) {

		// Check permissions.
		if ( is_user_logged_in() && ! current_user_can( 'edit_users' ) ) {
			return hp_rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\User_Password_Reset();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp_rest_error( 400, $form->get_errors() );
		}

		// Get user.
		$user = check_password_reset_key( $form->get_value( 'password_reset_key' ), $form->get_value( 'username' ) );

		if ( is_wp_error( $user ) ) {
			return hp_rest_error( 401, esc_html__( 'Password reset key is expired or invalid', 'hivepress' ) );
		}

		// Reset password.
		reset_password( $user, $form->get_value( 'password' ) );

		// Authenticate user.
		if ( ! is_user_logged_in() ) {
			wp_set_auth_cookie( $user->ID, true );
		}

		// Send email.
		wp_password_change_notification( $user );

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $user->ID,
				],
			],
			200
		);
	}

	/**
	 * Updates user.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function update_user( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp_rest_error( 401 );
		}

		// Get user.
		$user = Models\User::get( $request->get_param( 'id' ) );

		if ( is_null( $user ) ) {
			return hp_rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_users' ) && get_current_user_id() !== $user->get_id() ) {
			return hp_rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\User_Update();

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp_rest_error( 400, $form->get_errors() );
		}

		// Check password.
		if ( get_current_user_id() === $user->get_id() && ( $form->get_value( 'email' ) !== $user->get_email() || $form->get_value( 'password' ) ) ) {
			if ( $form->get_value( 'current_password' ) === null ) {
				return hp_rest_error( 400, esc_html__( 'Current password is required', 'hivepress' ) );
			}

			if ( ! wp_check_password( $form->get_value( 'current_password' ), $user->get_password(), $user->get_id() ) ) {
				return hp_rest_error( 401, esc_html__( 'Current password is incorrect', 'hivepress' ) );
			}
		}

		// Update user.
		$user->fill( $form->get_values() );

		if ( ! $user->save() ) {
			return hp_rest_error( 400, esc_html__( 'Error updating user', 'hivepress' ) );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $user->get_id(),
				],
			],
			200
		);
	}

	/**
	 * Deletes user.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function delete_user( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp_rest_error( 401 );
		}

		// Get user.
		$user = Models\User::get( $request->get_param( 'id' ) );

		if ( is_null( $user ) ) {
			return hp_rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'delete_users' ) && get_current_user_id() !== $user->get_id() ) {
			return hp_rest_error( 403 );
		}

		// Check password.
		if ( get_current_user_id() === $user->get_id() ) {
			$form = new Forms\User_Delete();

			$form->set_values( $request->get_params() );

			if ( ! $form->validate() ) {
				return hp_rest_error( 400, $form->get_errors() );
			}

			if ( ! wp_check_password( $form->get_value( 'password' ), $user->get_password(), $user->get_id() ) ) {
				return hp_rest_error( 401, esc_html__( 'Password is incorrect', 'hivepress' ) );
			}
		}

		// Delete user.
		if ( ! $user->delete() ) {
			return hp_rest_error( 400, esc_html__( 'Error deleting user', 'hivepress' ) );
		}

		return new \WP_Rest_Response( (object) [], 204 );
	}

	/**
	 * Renders login page.
	 *
	 * @return string
	 */
	public function render_login_page() {
		// todo.
	}

	/**
	 * Renders password page.
	 *
	 * @return string
	 */
	public function render_password_page() {
		// todo.
	}

	/**
	 * Renders settings page.
	 *
	 * @return string
	 */
	public function render_settings_page() {
		// todo.
	}
}
