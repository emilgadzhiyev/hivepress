<?php
/**
 * Result count block.
 *
 * @package HivePress\Blocks
 */

namespace HivePress\Blocks;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Result count block class.
 *
 * @class Result_Count
 */
class Result_Count extends Block {

	/**
	 * Renders block HTML.
	 *
	 * @return string
	 */
	public function render() {
		global $wp_query;

		$output = '';

		if ( $wp_query->found_posts > 0 ) {
			$output = '<div class="hp-result-count">';

			// Get first result.
			$first_result = 1;

			if ( hp\get_current_page() > 1 ) {
				$first_result = $wp_query->query_vars['posts_per_page'] * ( hp\get_current_page() - 1 ) + 1;
			}

			// Get last result.
			$last_result = $first_result + $wp_query->post_count - 1;

			$output .= sprintf( esc_html__( 'Showing %1$s-%2$s of %3$s results', 'hivepress' ), number_format_i18n( $first_result ), number_format_i18n( $last_result ), number_format_i18n( $wp_query->found_posts ) );

			$output .= '</div>';
		}

		return $output;
	}
}