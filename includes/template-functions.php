<?php


function wpmuio_preset_export_code() {
	global $post;

	$field_value = get_post_meta( $post->ID );
	echo $field_value['_wpmuio_export_data'][0];
}


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
			<?php
				// get the thumbnail URL
				$thumb_url = wp_get_attachment_url( get_post_thumbnail_id() );
				if ( $thumb_url == '' )
					$thumb_url = SHOESTRAP_PRESETS_URL . '/assets/img/empty.png';

				$args = array(
					"url"       => $thumb_url,
					"width"     => 691,
					"height"    => 424,
					"crop"      => true,
					"retina"    => "",
					"resize"    => true,
				);
				$image = shoestrap_image_resize( $args );

				echo '<img src="' . $image['url'] . '" style="width: 100%; height: auto;" />';
			?>

			<?php do_action( 'shoestrap_in_article_top' ); ?>

			<header style="height:3em; overflow: hidden;">
				<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			</header>

			<div class="entry-summary">
				<div class="overflow-expand"><?php echo $fields['_wpmuio_export_description'][0]; ?></div>
				<div class="clearfix" style="height:1em;"></div>
				<button class="btn btn-success btn-block" id="copy-button-<?php echo $post->ID; ?>" data-clipboard-text='<?php the_permalink(); ?>' title="<?php _e( 'Copy URL to Clipboard' ); ?>"><?php _e( 'Copy URL to Clipboard' ); ?></button>
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


function wpmuio_blankout_content() {
	if ( is_singular( 'presets' ) ) {
		add_action( 'shoestrap_page_header_override', function() {} );
		remove_filter('template_include', array('Roots_Wrapping', 'wrap'), 99);
		add_action( 'shoestrap_content_single_override', 'wpmuio_preset_export_code' );
	}
}
add_action( 'wp', 'wpmuio_blankout_content' );

function wpmuio_presets_explain_text() {
	if ( is_post_type_archive( 'presets' ) ) : ?>
		<div class="alert alert-info">
			<p>You can create your own presets and submit them here by exporting your Redux Options as a file.
			Then <a href="http://shoestrap.org/wp-admin/post-new.php?post_type=presets" class="btn btn-info">Create a new Preset</a></p>

			<p>To import a preset to your own site, simply click on the <strong>Copy URL to Clipboard</strong> button and then from your own installation go to Presentation &rarr; Theme Options &rarr; Import from URL.
			Paste the URL and hit "Import".
			That's all!</p>
		</div>
	<?php endif;
}
add_action( 'shoestrap_pre_main', 'wpmuio_presets_explain_text' );