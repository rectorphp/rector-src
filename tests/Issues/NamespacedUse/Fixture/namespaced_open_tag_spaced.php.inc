<?php
/**
 * @package xxx-gutenberg-blocks
 */

namespace XXX\plugins\GutenbergBlocks\CriticalCSS;

function load_css( string $block_name, string $style_path, string $type = 'block' ): void {
	if ( ! file_exists( $style_path ) ) {
		return;
	}
	?>
	<style class="nwp-gutenberg-blocks-<?php echo esc_attr( $block_name ); ?>-<?php echo esc_attr( $type ); ?>">
		<?php echo html_minify( file_get_contents( $style_path ) ); // phpcs:ignore ?>
	</style>
	<?php
}