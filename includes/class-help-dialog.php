<?php
/**
 * Shared admin help dialog renderer.
 *
 * @package Analytics_Report_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders accessible help triggers and dialogs for plugin admin screens.
 *
 * @since 0.1.0
 */
final class Analytics_Report_AI_Help_Dialog {

	/**
	 * Render a help trigger button.
	 *
	 * @param string $dialog_id    Dialog element ID.
	 * @param string $button_label Accessible button label.
	 * @param string $button_text  Visible button text.
	 * @return void
	 */
	public static function render_button( $dialog_id, $button_label, $button_text = '' ) {
		$button_text = '' === $button_text ? __( 'Help', 'studio317-report-drafts-google-analytics' ) : $button_text;
		?>
		<span class="studio317-report-drafts-google-analytics-help-control">
			<button
				type="button"
				id="<?php echo esc_attr( $dialog_id . '-button' ); ?>"
				class="button-link studio317-report-drafts-google-analytics-help-button"
				aria-label="<?php echo esc_attr( $button_label ); ?>"
				aria-controls="<?php echo esc_attr( $dialog_id ); ?>"
				aria-expanded="false"
				data-studio317-report-drafts-google-analytics-help-button
				data-studio317-report-drafts-google-analytics-dialog-target="<?php echo esc_attr( $dialog_id ); ?>"
			>
				<?php echo esc_html( $button_text ); ?>
			</button>
		</span>
		<?php
	}

	/**
	 * Render a generic help dialog.
	 *
	 * @param string $dialog_id    Dialog element ID.
	 * @param string $title        Dialog title.
	 * @param array  $items        Help list items.
	 * @param string $code_example Optional placeholder-only code example.
	 * @param string $list_type    List type. Accepts 'ul' or 'ol'.
	 * @return void
	 */
	public static function render_dialog( $dialog_id, $title, $items, $code_example = '', $list_type = 'ul' ) {
		$title_id  = $dialog_id . '-title';
		$list_type = 'ol' === $list_type ? 'ol' : 'ul';
		?>
		<div class="studio317-report-drafts-google-analytics-help-dialog-backdrop" id="<?php echo esc_attr( $dialog_id ); ?>" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $title_id ); ?>" tabindex="-1" hidden data-studio317-report-drafts-google-analytics-help-dialog>
			<div class="studio317-report-drafts-google-analytics-help-dialog">
				<button type="button" class="button-link studio317-report-drafts-google-analytics-help-dialog-close" data-studio317-report-drafts-google-analytics-help-close>
					<?php echo esc_html__( 'Close', 'studio317-report-drafts-google-analytics' ); ?>
				</button>
				<h3 id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( $title ); ?></h3>
				<?php if ( 'ol' === $list_type ) : ?>
					<ol>
						<?php foreach ( $items as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ol>
				<?php else : ?>
					<ul>
						<?php foreach ( $items as $item ) : ?>
							<li><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( '' !== $code_example ) : ?>
					<p><strong><?php echo esc_html__( 'Server configuration example:', 'studio317-report-drafts-google-analytics' ); ?></strong></p>
					<pre><code><?php echo esc_html( $code_example ); ?></code></pre>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
