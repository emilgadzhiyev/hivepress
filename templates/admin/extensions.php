<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use HivePress\Helpers as hp;
?>
<div class="wrap hp-page">
	<h1 class="hp-page__title"><?php echo esc_html( hivepress()->get_name() ); ?> <span>/</span> <?php esc_html_e( 'Extensions', 'hivepress' ); ?></h1>
	<hr class="wp-header-end">
	<?php if ( count( $tabs ) > 1 ) : ?>
		<ul class="subsubsub">
			<?php foreach ( $tabs as $active_tab => $tab_args ) : ?>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=hp_extensions&tab=' . $active_tab ) ); ?>" <?php if ( $active_tab === $current_tab ) : ?>class="current"<?php endif; ?>>
						<?php echo esc_html( $tab_args['name'] ); ?>
						<span class="count">(<?php echo esc_html( number_format_i18n( $tab_args['count'] ) ); ?>)</span>
					</a>
					<?php if ( hp\get_last_array_value( $tabs ) !== $tab_args ) : ?> |<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="wp-list-table widefat plugin-install">
		<?php foreach ( $extensions as $extension ) : ?>
			<div class="hp-extension plugin-card">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3>
							<?php echo esc_html( $extension['name'] ); ?>
							<img src="<?php echo esc_url( $extension['image_url'] ); ?>" class="plugin-icon" alt="<?php echo esc_attr( $extension['name'] ); ?>">
						</h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons">
							<li>
								<?php
								if ( 'install' === $extension['status'] ) :
									if ( isset( $extension['price'] ) ) :
										?>
										<a href="<?php echo esc_url( $extension['buy_url'] ); ?>" target="_blank" class="install-now button"><?php esc_html_e( 'Purchase', 'hivepress' ); ?></a>
									<?php else : ?>
										<a href="<?php echo esc_url( $extension['url'] ); ?>" class="install-now button"><?php esc_html_e( 'Install', 'hivepress' ); ?></a>
									<?php
									endif;
								elseif ( 'update_available' === $extension['status'] ) :
									?>
									<a href="<?php echo esc_url( $extension['url'] ); ?>" class="update-now button"><?php esc_html_e( 'Update', 'hivepress' ); ?></a>
								<?php elseif ( 'activate' === $extension['status'] ) : ?>
									<a href="<?php echo esc_url( $extension['url'] ); ?>" class="button activate-now"><?php esc_html_e( 'Activate', 'hivepress' ); ?></a>
								<?php else : ?>
									<button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html_x( 'Active', 'extension', 'hivepress' ); ?></button>
								<?php endif; ?>
							</li>
						</ul>
					</div>
					<div class="desc column-description">
						<p><?php echo esc_html( $extension['description'] ); ?></p>
						<p class="hp-extension__menu">
							<a href="<?php echo esc_url( $extension['docs_url'] ); ?>" target="_blank"><?php esc_html_e( 'Docs', 'hivepress' ); ?></a>
							<span>&nbsp;|&nbsp;</span>
							<a href="<?php echo esc_url( $extension['support_url'] ); ?>" target="_blank"><?php echo esc_html_x( 'Support', 'noun', 'hivepress' ); ?></a>
						</p>
					</div>
				</div>
				<div class="plugin-card-bottom">
					<div class="column-downloaded">
						<?php
						/* translators: %s: version. */
						printf( esc_html__( 'Version %s', 'hivepress' ), $extension['version'] );
						?>
					</div>
					<div class="column-compatibility">
						<strong class="hp-extension__price"><?php echo esc_html( hp\get_array_value( $extension, 'price', hivepress()->translator->get_string( 'free' ) ) ); ?></strong>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="clear"></div>
	</div>
</div>
