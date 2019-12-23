<?php
/**
 * Checkbox field.
 *
 * @package HivePress\Fields
 */

namespace HivePress\Fields;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Checkbox field class.
 *
 * @class Checkbox
 */
class Checkbox extends Field {

	/**
	 * Field type.
	 *
	 * @var string
	 */
	protected static $type;

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
	 * Checkbox caption.
	 *
	 * @var string
	 */
	protected $caption;

	/**
	 * Sample value.
	 *
	 * @var mixed
	 */
	protected $sample = true;

	/**
	 * Class initializer.
	 *
	 * @param array $args Field arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'type'     => 'CHAR',
				'title'    => esc_html__( 'Checkbox', 'hivepress' ),

				'settings' => [
					'caption' => [
						'label'      => esc_html__( 'Caption', 'hivepress' ),
						'type'       => 'text',
						'max_length' => 2048,
						'_order'      => 10,
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
				'filters'  => true,

				'statuses' => [
					'optional' => null,
				],
			],
			$args
		);

		parent::__construct( $args );
	}

	/**
	 * Bootstraps field properties.
	 */
	protected function bootstrap() {
		$attributes = [];

		// Set caption.
		if ( is_null( $this->caption ) ) {
			$this->caption = $this->label;
		}

		// Set ID.
		$attributes['id'] = explode( '[', $this->name )[0] . '_' . uniqid();

		// Set required flag.
		if ( $this->required ) {
			$attributes['required'] = true;
		}

		$this->attributes = hp\merge_arrays( $this->attributes, $attributes );

		parent::bootstrap();
	}

	/**
	 * Gets field display value.
	 *
	 * @return mixed
	 */
	public function get_display_value() {
		return $this->value ? esc_html__( 'Yes', 'hivepress' ) : esc_html__( 'No', 'hivepress' );
	}

	/**
	 * Normalizes field value.
	 */
	protected function normalize() {
		parent::normalize();

		if ( is_bool( $this->sample ) ) {
			$this->value = boolval( $this->value );
		} else {
			$this->value = wp_unslash( $this->value );
		}
	}

	/**
	 * Sanitizes field value.
	 */
	protected function sanitize() {
		if ( ! is_bool( $this->sample ) ) {
			$this->value = sanitize_text_field( $this->value );
		}
	}

	/**
	 * Renders field HTML.
	 *
	 * @return string
	 */
	public function render() {
		$output = '<label for="' . esc_attr( $this->attributes['id'] ) . '" class="' . esc_attr( implode( ' ', (array) hp\get_array_value( $this->attributes, 'class' ) ) ) . '">';

		unset( $this->attributes['class'] );

		$output .= '<input type="' . esc_attr( static::get_display_type() ) . '" name="' . esc_attr( $this->name ) . '" value="' . esc_attr( $this->sample ) . '" ' . checked( $this->value, $this->sample, false ) . ' ' . hp\html_attributes( $this->attributes ) . '>';
		$output .= '<span>' . hp\sanitize_html( $this->caption ) . '</span>';

		$output .= '</label>';

		return $output;
	}
}
