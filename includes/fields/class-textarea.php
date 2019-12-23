<?php
/**
 * Textarea field.
 *
 * @package HivePress\Fields
 */

namespace HivePress\Fields;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Textarea field class.
 *
 * @class Textarea
 */
class Textarea extends Text {

	/**
	 * Field title.
	 *
	 * @var string
	 */
	protected static $title;

	/**
	 * Field settings.
	 *
	 * @var array
	 */
	protected static $settings = [];

	/**
	 * Editor flag.
	 *
	 * @var mixed
	 */
	protected $editor = false;

	/**
	 * Class initializer.
	 *
	 * @param array $args Field arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'title'    => esc_html__( 'Textarea', 'hivepress' ),

				'settings' => [
					'editor' => [
						'label'   => esc_html__( 'Formatting', 'hivepress' ),
						'caption' => esc_html__( 'Allow HTML formatting', 'hivepress' ),
						'type'    => 'checkbox',
						'_order'   => 40,
					],
				],
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Class constructor.
	 *
	 * @param array $args Field arguments.
	 */
	public function __construct( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'filters' => false,
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps field properties.
	 */
	protected function bootstrap() {
		if ( $this->editor ) {

			// Set HTML flag.
			if ( empty( $this->html ) ) {
				$this->html = true;
			}

			// Set editor settings.
			if ( ! is_array( $this->editor ) ) {
				$this->editor = [
					'toolbar1'    => implode(
						',',
						[
							'bold',
							'italic',
							'underline',
							'strikethrough',
							'bullist',
							'numlist',
						]
					),
					'toolbar2'    => '',
					'toolbar3'    => '',
					'toolbar4'    => '',
					'elementpath' => false,
				];
			}
		}

		parent::bootstrap();
	}

	/**
	 * Sanitizes field value.
	 */
	protected function sanitize() {
		if ( empty( $this->html ) ) {
			$this->value = sanitize_textarea_field( $this->value );
		} else {
			parent::sanitize();
		}
	}

	/**
	 * Renders field HTML.
	 *
	 * @return string
	 */
	public function render() {
		$output = '';

		if ( $this->editor ) {
			ob_start();

			// Render editor.
			wp_editor(
				$this->value,
				$this->name,
				[
					'textarea_rows' => 5,
					'media_buttons' => false,
					'quicktags'     => false,
					'tinymce'       => $this->editor,
				]
			);

			$output .= ob_get_contents();
			ob_end_clean();
		} else {

			// Render textarea.
			$output .= '<textarea name="' . esc_attr( $this->name ) . '" ' . hp\html_attributes( $this->attributes ) . '>' . esc_textarea( $this->value ) . '</textarea>';
		}

		return $output;
	}
}
