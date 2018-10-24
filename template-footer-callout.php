<?php 
	$queried_object = get_queried_object();
	if( is_page() || is_single() ) {
		$id = get_the_ID();
	} elseif( is_home() ) {
		$id = get_option( 'page_for_posts' );
	} elseif( is_shop() ) {
		$id = get_option( 'woocommerce_shop_page_id' ); 
	} elseif( is_product_category() ) {
		$id = $queried_object->ID;
	} elseif( is_post_type_archive( 'key_issues' ) ) {
		$id = 'option';
	}

	$source = 'option';
	if( get_field('custom_callout', $id) == true ) {
		$source = $id;
	}
	$background_color = strtolower(get_field('background_color', $source));
	$callout_text = get_field('callout_text', $source);
	$link = get_field('link_to', $source);
	$link_to = get_the_permalink(get_field('link_to', $source));
	$link_text = get_field('link_text', $source);
	if(!$link_text) :
		$link_text = get_the_title($link);
	endif;

	if( get_field('toggle_callout', 'option' ) == true && get_field('toggle_callout', $id) == true ) : 
?>
	<div class="footer-callout <?php echo $background_color; ?>">
		<div class="footer-callout__content">
			<?php echo $callout_text; ?>
		</div>
		<div class="footer-callout__button">
			<a class="button" href="<?php echo $link_to; ?>">
				<?php echo $link_text; ?>
			</a>
		</div>
		<?php do_action( 'footer_callout' ); ?>
	</div>
<?php endif; ?>