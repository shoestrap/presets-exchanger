<?php

function wpmuio_preset_export_code() {
	global $post;

	$field_value = get_post_meta( $post->ID );
	?>

	<hr>

	<?php echo $field_value['_wpmuio_export_description'][0]; ?>

	<?php if ( !empty( $field_value['_wpmuio_export_description'][0] ) ) : ?><hr><?php endif; ?>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="col-md-6">
			<?php
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				echo '<a href="' . $large_image_url[0] . '" title="' . the_title_attribute( 'echo=0' ) . '" >';
				the_post_thumbnail( 'large' );
				echo '</a>';
			?>
		</div>
	<?php endif; ?>


	<?php if ( has_post_thumbnail() ) : ?><div class="col-md-6"><?php endif; ?>
		<div class="alert alert-info">
			<?php _e( 'Double-Click the text below to select it and then copy it to your dashboard. Once you do, you can import it to your Shoestrap theme from the import menu in the theme options.' ); ?>
		</div>
	<?php if ( has_post_thumbnail() ) : ?></div><?php endif; ?>

	<div class="clearfix"></div>
	<hr>

	<pre><?php echo $field_value['_wpmuio_export_data'][0]; ?></pre>

	<hr>

	<?php

}
add_action( 'shoestrap_single_pre_content', 'wpmuio_preset_export_code' );


function wpmuio_loop_override() {
	if ( is_post_type_archive( 'presets' ) )
		add_action( 'shoestrap_content_override', 'preset_content_template' );
}
add_action( 'shoestrap_index_begin', 'wpmuio_loop_override' );


function preset_content_template() {
	global $post;
	$fields = get_post_meta( $post->ID );
	?>

	<article <?php post_class( array( 'col-md-4' ) ); ?>>
		<div class="thumbnail">
			<?php do_action( 'shoestrap_in_article_top' ); ?>

			<header style="height:3em; overflow: hidden;">
				<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			</header>

			<div class="entry-summary">
				<button class="btn btn-success btn-block" id="copy-button-<?php echo $post->ID; ?>" data-clipboard-text="<?php echo $fields['_wpmuio_export_data'][0]; ?>" title="Copy to Clickboard">Copy to Clipboard</button>
				<script>var client = new ZeroClipboard( $("#copy-button-<?php echo $post->ID; ?>"), { moviePath: "<?php echo SHOESTRAP_PRESETS_URL; ?>/assets/js/ZeroClipboard.swf" } );</script>

			</div>

			<div class="clearfix"></div>
		</div>
		<?php do_action( 'shoestrap_in_article_bottom' ); ?>
	</article>
	<?php
}

function wpmuio_enqueue_scripts() {
	if ( is_post_type_archive( 'presets' ) ) {

		wp_register_script( 'presets_zero_clipboard', SHOESTRAP_PRESETS_URL . '/assets/js/ZeroClipboard.js' );
		wp_enqueue_script('presets_zero_clipboard');

		wp_register_script( 'presets_zero_clipboard_script', SHOESTRAP_PRESETS_URL . '/assets/js/script.js' );
		wp_enqueue_script('presets_zero_clipboard_script');
	}
}
add_action( 'wp_enqueue_scripts', 'wpmuio_enqueue_scripts' );