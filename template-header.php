<?php
	$queried_object = get_queried_object();
	$the_id = get_the_ID();
	$header = null;
	$wayfinder = '';

	if( is_page() ) {
		$header = get_the_title();
	} elseif( is_home() ) {
		$header = get_the_title( get_option( 'page_for_posts' ) );
	} elseif( is_single() ) {
		$header = get_the_title();
		if( get_field('wayfinder_color', get_the_ID()) ) {
			$wayfinder = strtolower( get_field( 'wayfinder_color', get_the_ID() ) );
		}
	} elseif( is_shop() ) {
		$header = get_the_title( get_option( 'woocommerce_shop_page_id' ) ); 
	} elseif( is_product_category() || is_tax() ) {
		$header = $queried_object->name;
	} elseif( is_search() ) {
		$header = 'Search Results';
	} elseif( is_post_type_archive() ) {
		$header = $queried_object->label;
	} 

	if($queried_object->name == 'tribe_events') {
		$header = 'Calendar';
	}
	
?>

<aside class="msw-page-header <?php echo $wayfinder; ?>">
	<h1><?php echo $header; ?></h1>
	<img src="<?php echo get_bloginfo( 'stylesheet_directory' ); ?>/assets/white-chat-icons.png">
	<?php do_action( 'msw_page_header_content' ); ?>
</aside>
<?php
	if( function_exists( 'yoast_breadcrumb' ) ){
		yoast_breadcrumb( '<div id="breadcrumbs">', '</div>' );
	}
?>