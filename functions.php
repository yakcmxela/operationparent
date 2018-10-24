<?php

class MakespaceChild {

	function __construct(){
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'acf/init', array( $this, 'msw_acf_init' ) );
		add_action( 'wp_loaded', array( $this, 'msw_loaded' ) );
		add_action( 'init', array( $this, 'msw_ajax_atc') );
		$this->split_column_gform();
		$this->key_issues_post_type();
		$this->key_issues_admin_menu();
		$this->resource_manager_post_type();
		$this->resource_manager_admin_menu();
		$this->resource_manager_taxonomy();
		$this->resource_manager_post_count();
		$this->testimonial_post_type();
		$this->testimonial_admin_page();
		$this->woo_adds();
		$this->woo_removes();
		$this->woo_filters();
		$this->yoast_breadcrumbs();

		//add_filter( 'wpseo_breadcrumb_links', array( $this, 'add_cpt_archive_parent_breadcrumb' ), 10, 1);
	}

	function add_cpt_archive_parent_breadcrumb( $crumbs ){
		$archive_crumbs = array();
		$post_type;

		// Section for adding the parent one level from the end
		if ( is_post_type_archive() || is_tax() ) {
			if ( is_tax() ) {
				$tax_name = get_queried_object()->taxonomy;
				$module_end = strpos($tax_name, "module") + strlen( "module" );
				$post_type = substr($tax_name, 0, $module_end );
			} else {
				$post_type = get_queried_object()->name;
			}
			$field_name = $post_type . '_parent';
			$archive_parent = get_field( $field_name, 'option' )->ID;

			if( isset( $archive_parent ) ){
				array_push( $archive_crumbs, array('id' => $archive_parent), array_pop( $crumbs ) );
				$crumbs = array_merge( $crumbs, $archive_crumbs);
			}
		}

		// Section for adding the parent two levels from the end
		if ( is_singular() ) {
			$post_type = get_post_type();
			$field_name = $post_type . '_parent';
			$archive_parent = get_field( $field_name, 'option' )->ID;

			if( isset( $archive_parent ) ){
				array_push( $archive_crumbs, array_pop( $crumbs ), array_pop( $crumbs ), array('id' => $archive_parent) );
				$archive_crumbs = array_reverse( $archive_crumbs);
				$crumbs = array_merge( $crumbs, $archive_crumbs);
			}
		}
		return $crumbs;
	}

	function after_setup_theme(){
		//add_theme_support( 'case-studies-module' );
		add_theme_support( 'locations-module' );
		// add_theme_support( 'staff-module' );
	}

	function wp_enqueue_scripts(){
		$msw_object = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'home_url' => home_url(),
			'show_dashboard_link' => current_user_can( 'manage_options' ) ? 1 : 0,
			'site_url' => site_url(),
			'stylesheet_directory' => get_stylesheet_directory_uri(),
		);
		if ( get_theme_support( 'locations-module' ) ) {
		 	$msw_object['google_map_data'] = get_google_map_data();
		}

		if ( get_field( 'default_google_map_api_key', 'option' ) ) :
			$google_api_key = 'https://maps.googleapis.com/maps/api/js?key=' . get_field( 'default_google_map_api_key', 'option' );
			wp_enqueue_script('google-maps', $google_api_key, true);
		endif;

		wp_enqueue_script( 'theme', get_stylesheet_directory_uri() . '/scripts.min.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/scripts.min.js' ) );
		wp_localize_script( 'theme', 'MSWObject', $msw_object );

		wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Lato:300,400,700|Poppins:300,400,500,600|Shadows+Into+Light+Two' );
		wp_enqueue_style( 'theme', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
	}

	function msw_acf_init() {
		if ( get_field( 'default_google_map_api_key', 'option' ) ) :
			acf_update_setting('google_api_key', get_field( 'default_google_map_api_key', 'option' ));
		endif;
	}

	function msw_loaded() {
		// Custom Thumbnail Sizes
		add_theme_support( 'post-thumbnails' );
		// add_image_size( 'blog-image', 400, 300, true ); // Example
	}

	function msw_ajax_atc() {
		// Example use case for shop archive page
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_action( 'woocommerce_after_shop_loop_item', 'ms_ajax_shop', 10 );
		function ms_ajax_shop() {
			global $product;
			$type = $product->get_type();
			if($type == 'simple') {
				$atc_icon = '<img src="' . get_bloginfo('stylesheet_directory') . '/assets/white-cart.svg">';
				echo prepare_ajax_atc($product, 'add-to-cart', $atc_icon, 'simple'); 
			}
		}

		add_action( 'wp_ajax_ms_ajax_atc', 'ms_ajax_atc' );
		add_action( 'wp_ajax_nopriv_ms_ajax_atc', 'ms_ajax_atc' );
		function ms_ajax_atc() {
			do_ajax_atc( $_POST['woo_ajax_object'] );
		}
	}

	function split_column_gform() {
		function gform_column_splits($content, $field, $value, $lead_id, $form_id, $form) {
			if(!is_admin()) { // only perform on the front end
			    if($field['type'] == 'section') {
			        $form = RGFormsModel::get_form_meta($form_id, true);

			        // check for the presence of multi-column form classes
			        $form_class = explode(' ', $form['cssClass']);
			        $form_class_matches = array_intersect($form_class, array('two-column', 'three-column'));

			        // check for the presence of section break column classes
			        $field_class = explode(' ', $field['cssClass']);
			        $field_class_matches = array_intersect($field_class, array('gform_column'));

			        // if field is a column break in a multi-column form, perform the list split
			        if(!empty($form_class_matches) && !empty($field_class_matches)) { // make sure to target only multi-column forms

			            // retrieve the form's field list classes for consistency
			            $description_class = rgar($form, 'descriptionPlacement') == 'above' ? 'description_above' : 'description_below';

			            // close current field's li and ul and begin a new list with the same form field list classes
			            return '</li></ul><ul class="gform_fields '.$form['labelPlacement'].' '.$description_class.' '.$field['cssClass'].'"><li class="gfield gsection empty">';

			        }
			    }
			}
			return $content;
		}
		add_filter('gform_field_content', 'gform_column_splits', 100, 6);
	}

	function key_issues_post_type() {
		$labels = array(
			'name' => __( 'Key Issues', '' ),
			'singular_name' => __( 'Key Issue', '' ),
			'menu_name' => __( 'Key Issues', '' ),
			'all_items' => __( 'All Key Issues', '' ),
			'edit_item' => __( 'Edit Key Issue', '' ),
			'new_item' => __( 'New Key Issue', '' ),
			'view_item' => __( 'View Key Issue', '' ),
			'search_items' => __( 'Search Key Issues', '' ),
			'not_found' => __( 'No Key Issues Found', '' ),
			'not_found_in_trash' => __( 'No Key Issues Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'Key Issue', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'key-issues', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'capability_type' => 'post',
			'map_meta_cap' => true,
		);

		register_post_type( 'key_issues', $args );
	}

	function key_issues_admin_menu(){
		acf_add_options_sub_page( array(
			'page_title' => 'Key Issues',
			'menu_title' => 'KI Overview Page',
			'menu_slug' => 'makespace-key-issues-overview',
			'parent_slug' => 'edit.php?post_type=key_issues'
		) );
	}

	function resource_manager_post_type() {
		$labels = array(
			'name' => __( 'Resource Manager', '' ),
			'singular_name' => __( 'Resource', '' ),
			'menu_name' => __( 'Resource Manager', '' ),
			'all_items' => __( 'All Resources', '' ),
			'edit_item' => __( 'Edit Resource', '' ),
			'new_item' => __( 'New Resource', '' ),
			'view_item' => __( 'View Resource', '' ),
			'search_items' => __( 'Search Resources', '' ),
			'not_found' => __( 'No Resources Found', '' ),
			'not_found_in_trash' => __( 'No Resources Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'Resource Manager', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'resource-manager', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'capability_type' => 'post',
		);

		register_post_type( 'resource_manager', $args );
	}

	function resource_manager_admin_menu(){
		acf_add_options_sub_page( array(
			'page_title' => 'Resource Manager',
			'menu_title' => 'RM Overview Page',
			'menu_slug' => 'makespace-resource-manager-overview',
			'parent_slug' => 'edit.php?post_type=resource_manager'
		) );
	}

	function resource_manager_taxonomy() {
		$args = array(
			'hierarchical' => true,
			'label' => "Resource Type",
			'show_in_menu' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'public' => true,
			'publicly_queryable' => true,
			'rewrite' => array( 'slug' => 'resource-type' ),
		);
		register_taxonomy( 'resource_type', array( 'resource_manager' ), $args );
	}

	function resource_manager_post_count() {
		function set_posts_per_page( $query ) {
		  if ( !is_admin() && $query->is_main_query() && is_post_type_archive( 'resource_manager' ) ) {
		    $query->set( 'posts_per_page', '-1' );
		  }
		}
		add_action( 'pre_get_posts', 'set_posts_per_page' );
	}

	function testimonial_post_type() {
		$labels = array(
			'name' => __( 'Testimonials', '' ),
			'singular_name' => __( 'Testimonial', '' ),
			'menu_name' => __( 'Testimonials', '' ),
			'all_items' => __( 'All Testimonials', '' ),
			'edit_item' => __( 'Edit Testimonial', '' ),
			'new_item' => __( 'New Testimonial', '' ),
			'view_item' => __( 'View Testimonial', '' ),
			'search_items' => __( 'Search Testimonials', '' ),
			'not_found' => __( 'No Testimonials Found', '' ),
			'not_found_in_trash' => __( 'No Testimonials Found In Trash', '' ),
		);

		$args = array(
			'label' => __( 'Testimonial', '' ),
			'labels' => $labels,
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_rest' => false,
			'rest_base' => '',
			'has_archive' => true,
			'show_in_menu' => true,
			'exclude_from_search' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => 'testimonials', 'with_front' => false ),
			'query_var' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'capability_type' => 'post',
			'map_meta_cap' => true,
		);

		register_post_type( 'testimonials', $args );
	}

	function testimonial_admin_page() {
		acf_add_options_sub_page( array(
			'page_title' => 'Testimonials Overview',
			'menu_title' => 'Testimonials Overview Page',
			'menu_slug' => 'makespace-testimonial-overview',
			'parent_slug' => 'edit.php?post_type=testimonials'
		) );
	}

	function woo_adds() {
		// Archive
		add_action( 'woocommerce_before_main_content' , 'add_container', 1 );
		add_action( 'woocommerce_archive_description', 'shop_sort', 11 );
		add_action( 'woocommerce_shop_loop_item_title', 'title_container', 9 );
		add_action( 'woocommerce_shop_loop_item_title', 'edition', 11 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'close_div', 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'display_single_price', 9 );
		add_action( 'woocommerce_before_shop_loop_item', 'atc', 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'product_image_background_image', 10 );
		add_action( 'woocommerce_after_main_content', 'close_div', 11 );

		function shop_sort() {
			echo get_template_part( 'template', 'filter-dropdowns' );
		}
		
		function product_image_background_image() {
			global $product;
			$image_id = $product->get_image_id();
			$image_url = wp_get_attachment_image_url( $image_id, 'medium_large' );
			$placeholder_url = get_bloginfo( 'stylesheet_directory' ) . '/assets/white-chat-icons.png';
			$alt = $product->get_name();
			if($image_url == false) {
				$image = '<div class="product-image placeholder" alt="' . $alt . '" style="background-image: url(' . $placeholder_url . ')"></div>';
			} else {
				$image = '<div class="product-image" alt="' . $alt . '" style="background-image: url(' . $image_url . ')"></div>';
			}
			
			echo $image;
		}
		function add_container() {
			echo '<div class="container">';
		} 
		function close_div() {
			echo '</div>';
		}
		function display_single_price() {
			global $product;
			$type = $product->get_type();
			$is_book = true;
			$categories = get_the_terms($product->get_ID(), 'product_cat');
			foreach( $categories as $category ) {
				if($category->slug === 'events') {
					$is_book = false;
				}
			}
			if($type == 'variable' && $is_book == true) {
				if(is_product()) {
					echo '<h2>';
				}
				echo '$' . $product->get_variation_price('min');
				if(is_product()) {
					echo '</h2>';
				}
			} elseif($type == 'grouped') {
			} else {
				if(is_product()) {
					echo '<h2>';
				}
				echo '<div class="price">' . $product->get_price_html() . '</div>';
				if(is_product()) {
					echo '</h2>';
				}
			}
		}
		function atc() {
			global $product;
			$type = $product->get_type();
			if($type == 'simple') {
				$atc_icon = '<img src="' . get_bloginfo('stylesheet_directory') . '/assets/white-cart.svg">';
				echo prepare_ajax_atc($product, 'add-to-cart', $atc_icon, 'simple');
			}
		}
		function title_container() {
			echo '<div class="product-meta">';
		}
		function edition() {
			global $post;
			if( get_field('edition', $post) ) {
				echo '<span class="edition">' . get_field('edition', $post) . '</span>';
			}
		}

		// Single
		add_action( 'woocommerce_before_single_product', 'product_title', 11 );
		add_action( 'woocommerce_single_product_summary', 'display_single_price', 10 );
		add_action( 'woocommerce_before_single_product', 'edition', 12 );
		add_action( 'woocommerce_before_single_product_summary', 'summary_container', 15 );
		add_action( 'woocommerce_single_product_summary', 'hide_input_opener', 20 );
		add_action( 'woocommerce_single_product_summary', 'close_div', 31 );
		add_action( 'woocommerce_single_product_summary', 'product_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', 'close_div', 50 );
		add_action( 'woocommerce_single_product_summary', 'close_div', 51 );
		add_action( 'woocommerce_single_product_summary', 'close_div', 52 );
		add_action( 'woocommerce_grouped_add_to_cart', 'link_to_products', 30 );

		function product_title() {
			global $product;
			echo '<h2>' . esc_html($product->get_name()) . '</h2>';
		}

		function summary_container() {
			echo '<div class="product-summary-container">';
		}

		function hide_input_opener() {
			global $product;
			if( get_field('is_sponsorship', $product->get_ID()) ==  true ) {
				echo '<div class="sponsorship-quantity">';
			};
		}

		function product_excerpt() {
			global $product;
			echo wpautop($product->get_description());
		}

		function upsell_container() {
			echo '<div class="upsells">';
		}

		function link_to_products() {
			global $product;
			$children = $product->get_children();
			$output = '';
			foreach($children as $child) {
				$is_sponsorship = get_field('is_sponsorship', $child);
				if($is_sponsorship == true) {
					$link_text = get_field('sponsorship_link_text', $child);
				} else {
					$link_text = 'Learn More';
				}
				$subproduct = wc_get_product($child);
				$output .= '<div class="grouped-product-link">';
				$output .= '<div class="product-info">';
				$output .= '<h3>' .  $subproduct->get_title() . '</h3>';
				$output .= '<p>' . wpautop($subproduct->get_description( 'view' )) . '</p>';
				$output .= '</div>';
				$output .= '<a class="button" href=' . $subproduct->get_permalink() . '">' . $link_text . '</a>';
				$output .= '</div>';
			}
			echo $output;
		}

		// Cart
		add_action( 'woocommerce_cart_calculate_fees','handling_fee' );
		add_action( 'woocommerce_applied_coupon', 'apply_shipping_on_coupon' );
		add_filter( 'woocommerce_cart_totals_coupon_label', 'display_coupon_description', 10, 2 );	

		function handling_fee() {
			global $woocommerce;
			if ( is_admin() && ! defined( 'DOING_AJAX' ) )
			  return;
			$downloadable_items = 0;
			$variable_items = 0;

			$cart_count = $woocommerce->cart->get_cart_contents_count();
			$cart_items = $woocommerce->cart->get_cart();
			foreach( $cart_items as $cart_item) {
				$product = wc_get_product($cart_item['product_id']);
				$product_type = $product->get_type();
				if($product_type == 'variable') {
					$cart_count = ($cart_count - $cart_item['quantity']);
					$variation = $cart_item['variation']['attribute_pa_volume'];
					$variation_split = explode('-', $variation);
					$quantity = intval($variation_split[0]);
					$additional = ($quantity * $cart_item['quantity']);
					$cart_count = ($cart_count + $additional);

				}
				if($product->is_downloadable() == true) {
					$downloadable_items = $downloadable_items + $cart_item['quantity'];
				}
			};
			if($downloadable_items == $cart_count) {
				return;
			}
			$cart_count = $cart_count - $downloadable_items;
			$fee = 0.00;
			if($cart_count < 50) {
				$fee = 0.00;
			} elseif($cart_count > 49 && $cart_count < 100) {
				$fee = 10.00;
			} elseif($cart_count > 99 && $cart_count < 501) {
				$fee = 15.00;
			} else {
				$fee = 20.00;
			}

			$woocommerce->cart->add_fee( 'Handling', $fee, true, 'standard' );
		}

		function apply_shipping_on_coupon( ) {
		    global $woocommerce;
		    $cart = $woocommerce->cart;
		    $coupon_code = strtolower('CADCAMYTI2018');
		    $cart_items = $woocommerce->cart->get_cart();
		    $total_quantity = 0;
			foreach( $cart_items as $cart_item) {
				$product = wc_get_product($cart_item['product_id']);
				$variation = $cart_item['variation']['attribute_pa_volume'];
				$variation_split = explode('-', $variation);
				$quantity = intval($variation_split[0]);
				$additional = $quantity * $cart_item['quantity'];
				$total_quantity = $total_quantity + $additional;
			};
			if($total_quantity > 99) {
				if(in_array($coupon_code, $woocommerce->cart->get_applied_coupons())){
			    	add_filter( 'woocommerce_package_rates', 'reduced_shipment_costs', 10, 2 );
			    	function reduced_shipment_costs( $rates, $package ) {
			    		foreach( $rates as $rate ) {
			    			$cost = floatval($rate->cost);
							if( $cost < 200.01 ) {
								$rate->cost = 0;
							} else {
								$rate->cost = ($cost - 200);
							}

						}
						return $rates;
			    	}
			    }
			}
		    
		}

		function display_coupon_description( $label, $coupon ) {
			global $woocommerce;
		    $cart = $woocommerce->cart;
		    $coupon_code = strtolower('CADCAMYTI2018');
		    $cart_items = $woocommerce->cart->get_cart();
		    $total_quantity = 0;
			foreach( $cart_items as $cart_item) {
				$product = wc_get_product($cart_item['product_id']);
				$variation = $cart_item['variation']['attribute_pa_volume'];
				$variation_split = explode('-', $variation);
				$quantity = intval($variation_split[0]);
				$additional = $quantity * $cart_item['quantity'];
				$total_quantity = $total_quantity + $additional;
			};
			if($coupon->get_code() == strtolower('CADCAMYTI2018')) {
				if($total_quantity < 99) {
					return 'A minimum of 100 items is required to apply this coupon.';
				}
				if ( is_callable( array( $coupon, 'get_description' ) ) ) {
					$description = $coupon->get_description();
				} else {
					$coupon_post = get_post( $coupon->id );
					$description = ! empty( $coupon_post->post_excerpt ) ? $coupon_post->post_excerpt : null;
				}
				return $description ? sprintf( esc_html__( 'Coupon: %s', 'woocommerce' ), $description ) : esc_html__( 'Coupon', 'woocommerce' );
			}
		}
	}

	function woo_removes() {
		// Archive
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); 

		// Single
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
	}

	function woo_filters () {
		add_filter( 'gettext', 'translate_text' );
		add_filter( 'ngettext', 'translate_text' );
		add_filter( 'woocommerce_pagination_args', 	'pagination_carets' );
		add_filter( 'woocommerce_output_related_products_args', 'upsell_cols' );
		add_filter( 'woocommerce_show_page_title', 'wc_hide_page_title' );
		add_filter( 'woocommerce_get_item_data', 'edition_cart', 10, 2 );
		add_filter( 'woocommerce_package_rates', 'bulk_shipments', 10, 2 );
		add_filter( 'woocommerce_quantity_input_max', 'woocommerce_quantity_input_max_callback', 10, 2 );
		add_filter( 'woocommerce_quantity_input_min', 'woocommerce_quantity_input_min_callback', 10, 2 );
		
		function translate_text($translated) {
			$translated = str_ireplace('Related Products', 'Other Products You May Like', $translated);
			return $translated;
		}
		function pagination_carets( $args ) {
			$args['prev_text'] = '<i class="fa fa-angle-left"></i>';
			$args['next_text'] = '<i class="fa fa-angle-right"></i>';
			return $args;
		}
		function upsell_cols( $args ) {
			$args['posts_per_page'] = 3;
			$args['columns'] = 3;
			return $args;
		}
		function wc_hide_page_title() {
			if( !is_shop() && !is_product_category() ) 
				return true;
		}
		function edition_cart( $item_data, $cart_item ) {
			global $post;
			$product_post = get_post($cart_item['product_id']);
			if(!get_field('edition', $product_post)) {
				return $item_data;
			}
			$item_data[] = array(
				'key' => __('Edition'),
				'value' => wc_clean( get_field('edition', $product_post) ),
				'display' => '',
			);
			return $item_data;
		}
		function bulk_shipments( $rates, $package ) {
			if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        		return;
        	$cart_items = $package['contents'];
        	$usps = false;
        	$ups = false;
        	foreach ($cart_items as $cart_item) {
        		$shipping_class_id = wc_get_product($cart_item['variation_id'])->get_shipping_class_id();
        		$shipping_class = get_term_by('id', $shipping_class_id, 'product_shipping_class');
        		$to_remove = '';
        		if($shipping_class->slug == 'usps') {
        			$usps = true;
        		} elseif($shipping_class->slug == 'bulk-ups') {
        			$ups = true;
        		}
        	}
        	$needle = '';
        	if( $usps == true && $ups == false ) {
	        	$needle = 'ups';
			} elseif( $usps == false && $ups == true ) {
				$needle = 'usps';
			} elseif( $usps == true && $ups == true ) {
				$needle = 'usps';
			}

			foreach ($rates as $key => $value) {
				if(strpos($key, $needle)) {
					unset($rates[$key]);
				};
			}
			return $rates;
		}
		function woocommerce_quantity_input_max_callback( $max, $product ) {
			$max = 9;  
			return $max;
		}
		function woocommerce_quantity_input_min_callback( $min, $product ) {
			$min = 1;  
			return $min;
		}
	}

	function yoast_breadcrumbs() {
		add_filter('wpseo_breadcrumb_output', 'change_breadcrumb_names');
		function change_breadcrumb_names( $output) {
			global $post;
		    if($post->post_type == 'tribe_events') {
		        $output = str_replace('Events', 'Calendar', $output);
		    }
		    return $output;
		}
	}

}

$MakespaceChild = new MakespaceChild();