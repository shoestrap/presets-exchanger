<?php

function wpmuio_preset_export_code() {
	global $post;

	$field_value = get_post_meta( $post->ID );
	?>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="col-md-6"><?php the_post_thumbnail( 'large' ); ?></div>
	<?php endif; ?>

	<hr>

	<?php echo $field_value['_wpmuio_export_description'][0]; ?>

	<hr>

	<?php if ( has_post_thumbnail() ) : ?><div class="col-md-6"><?php endif; ?>
		<div class="alert alert-info">
			<?php _e( 'Double-Click the text below to select it and then copy it to your dashboard. Once you do, you can import it to your Shoestrap theme from the import menu in the theme options.' ); ?>
		</div>
	<?php if ( has_post_thumbnail() ) : ?></div><?php endif; ?>

	<pre><?php echo $field_value['_wpmuio_export_data'][0]; ?></pre>

	<hr>

	<?php

}
add_action( 'shoestrap_single_pre_content', 'wpmuio_preset_export_code' );