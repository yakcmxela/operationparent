<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
		global $woocommerce;
		$cart_count = $woocommerce->cart->get_cart_contents_count();
		if($cart_count == 0) {
			$cart_count = '';
		}
		$location_args = array(
			'meta_key' => 'primary_location',
			'numberposts' => 1,
			'orderby' => 'meta_value',
			'post_type' => 'locations_module',
		);
		$locations = get_posts( $location_args );
		foreach ($locations as $location) {
			$street = get_field( 'street_address_line_2', $location);
			$city = get_field( 'city', $location );
			$state = get_field( 'state_region', $location );
			$zip = get_field( 'zip_postal_code', $location );
			$phone = get_field( 'phone', $location );
			$email = get_field( 'email', $location );
			$google_map_details = get_field( 'google_map', $location );
			$google_maps_link = get_field( 'google_maps_link', $location );
		}
		wp_reset_postdata();
	?>
	<header class="site-header">
		<div class="inner">
			<div class="site-header__logo">
				<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>" class="brand">
					<?php if( get_field('default_logo_svg', 'option') ) : ?>
						<?php sanitize_text_field( the_field('default_logo_svg', 'option') ); ?>
					<?php else : ?>
						<img src="<?php the_field( 'default_logo', 'option' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
					<?php endif; ?>
				</a>
			</div>
			<div class="toolbar">
				<div class="search">
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ) ?>">
						<label>Search</label>
						<img src="<?php echo bloginfo( 'stylesheet_directory') ?>/assets/search-symbol.svg">
						<input type="search" name="s" id="search">
					</form>
				</div>
				<div class="calendar">
					<a href="<?php echo get_site_url() ?>/calendar/">
						<svg xmlns="http://www.w3.org/2000/svg" width="60" height="58" viewBox="0 0 60 58">
						    <path fill="#FFF" fill-rule="evenodd" d="M22.444 47.445v-1.436c0-.539.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.436a.976.976 0 0 1-.977.979h-1.433a.98.98 0 0 1-.977-.98zM10.891 30.188v-1.433c0-.54.438-.98.977-.98h1.437c.54 0 .978.44.978.98v1.433a.978.978 0 0 1-.978.977h-1.437a.977.977 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.436a.98.98 0 0 1-.977.979H34.97a.98.98 0 0 1-.976-.98zm-11.55-8.628v-1.435c0-.54.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.435c0 .54-.433.98-.977.98h-1.433a.98.98 0 0 1-.977-.98zm-11.553 8.628v-1.436c0-.539.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.436a.98.98 0 0 1-.978.979h-1.437a.979.979 0 0 1-.977-.98zm0-8.628v-1.435c0-.54.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.435a.98.98 0 0 1-.978.98h-1.437a.98.98 0 0 1-.977-.98zm11.553-8.629v-1.433c0-.54.438-.98.977-.98h1.433c.544 0 .977.44.977.98v1.433a.975.975 0 0 1-.977.977h-1.433a.978.978 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.436a.98.98 0 0 1-.976.979h-1.434a.98.98 0 0 1-.977-.98zm0-17.257v-1.433c0-.54.438-.98.977-.98h1.434a.98.98 0 0 1 .976.98v1.433a.978.978 0 0 1-.976.977h-1.434a.978.978 0 0 1-.977-.977zm0 8.63v-1.436c0-.54.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.435a.98.98 0 0 1-.976.98h-1.434a.98.98 0 0 1-.977-.98zm-11.553 0v-1.436c0-.54.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.435a.98.98 0 0 1-.977.98H34.97a.98.98 0 0 1-.976-.98zm0-8.63v-1.433c0-.54.437-.98.976-.98h1.438a.98.98 0 0 1 .977.98v1.433a.978.978 0 0 1-.977.977H34.97a.977.977 0 0 1-.976-.977zm23.153-11.135H2.683V8.901a3.005 3.005 0 0 1 3-3.002h5.401v2.264a3.404 3.404 0 0 0 6.807 0V5.9h24.048v2.264a3.404 3.404 0 0 0 6.806 0V5.9h5.397A3.007 3.007 0 0 1 57.147 8.9v10.152zm0 32.728a3.008 3.008 0 0 1-3.005 3.005H5.683c-1.655 0-3-1.348-3-3.005V21.413h54.464v30.368zM13.444 3.456c0-.576.465-1.044 1.041-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.041-1.042V3.456zm30.854 0c0-.576.466-1.044 1.042-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.042-1.042V3.456zm13.379 52.33c1.173-1.053 1.83-2.405 1.83-4.005V8.901a5.369 5.369 0 0 0-5.365-5.362h-5.397v-.083A3.409 3.409 0 0 0 45.34.053a3.406 3.406 0 0 0-3.401 3.403v.083H17.89v-.083A3.409 3.409 0 0 0 14.485.053a3.406 3.406 0 0 0-3.4 3.403v.083H5.682A5.368 5.368 0 0 0 .323 8.9v42.88c0 1.6.756 3.077 1.83 4.005 1.073.928 2.054 1.175 2.512 1.256.332.065.67.104 1.018.104h48.46c.35 0 .69-.04 1.017-.104.687-.168 1.344-.203 2.517-1.256z"/>
						</svg>
					</a>
				</div>
				<div class="donate">
					<a href="<?php echo get_the_permalink(get_field('donate_link_to', 'option')); ?>">
						<img src="<?php echo bloginfo( 'stylesheet_directory') ?>/assets/donate-symbol.svg">
						<span>Donate</span>
					</a>
				</div>
				<div class="cart">
					<?php 
						$cart_class = '';
						if($cart_count !== '') :
							$cart_class = 'has-items';
						endif;
					?>
					<a href="<?php echo wc_get_cart_url(); ?>" class="<?php echo $cart_class; ?>" title="Cart" data-count="<?php echo $cart_count; ?>">
						<img src="<?php echo bloginfo( 'stylesheet_directory'); ?>/assets/white-cart.svg" alt="Cart">
					</a>
				</div>
			</div>
			<button class="nav-toggle" type="button" id="nav-toggle">
				<span class="line"></span>
				<span class="menu-title">MENU</span>
				<span class="line"></span>
			</button>
			<div class="site-header__menu">
				<?php
					wp_nav_menu( array(
						'container' => 'nav',
						'container_id' => 'large-nav-primary',
						'theme_location' => 'primary',
						'before' => '<span class="link-wrap">',
						'after' => '<span class="sub-menu-button"></span></span>'
					) );
				?>
				<div class="toolbar__lower">
					<div class="icons">
						<div class="search">
							<form role="search" method="get" class="search-form" action="' . home_url( '/' ) . '">
								<label>Search</label>
								<img src="<?php echo bloginfo( 'stylesheet_directory') ?>/assets/search-symbol.svg">
								<input type="search" name="s" id="search">
							</form>
						</div>

						<div class="donate">
							<a href="<?php echo get_the_permalink(get_field('donate_link_to', 'option')); ?>">
								<img src="<?php echo bloginfo( 'stylesheet_directory') ?>/assets/donate-symbol.svg">
								<span>Donate</span>
							</a>
						</div>
						<div class="calendar">
							<a href="<?php echo get_site_url() ?>/calendar/">
								<svg xmlns="http://www.w3.org/2000/svg" width="60" height="58" viewBox="0 0 60 58">
								    <path fill="#FFF" fill-rule="evenodd" d="M22.444 47.445v-1.436c0-.539.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.436a.976.976 0 0 1-.977.979h-1.433a.98.98 0 0 1-.977-.98zM10.891 30.188v-1.433c0-.54.438-.98.977-.98h1.437c.54 0 .978.44.978.98v1.433a.978.978 0 0 1-.978.977h-1.437a.977.977 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.436a.98.98 0 0 1-.977.979H34.97a.98.98 0 0 1-.976-.98zm-11.55-8.628v-1.435c0-.54.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.435c0 .54-.433.98-.977.98h-1.433a.98.98 0 0 1-.977-.98zm-11.553 8.628v-1.436c0-.539.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.436a.98.98 0 0 1-.978.979h-1.437a.979.979 0 0 1-.977-.98zm0-8.628v-1.435c0-.54.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.435a.98.98 0 0 1-.978.98h-1.437a.98.98 0 0 1-.977-.98zm11.553-8.629v-1.433c0-.54.438-.98.977-.98h1.433c.544 0 .977.44.977.98v1.433a.975.975 0 0 1-.977.977h-1.433a.978.978 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.436a.98.98 0 0 1-.976.979h-1.434a.98.98 0 0 1-.977-.98zm0-17.257v-1.433c0-.54.438-.98.977-.98h1.434a.98.98 0 0 1 .976.98v1.433a.978.978 0 0 1-.976.977h-1.434a.978.978 0 0 1-.977-.977zm0 8.63v-1.436c0-.54.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.435a.98.98 0 0 1-.976.98h-1.434a.98.98 0 0 1-.977-.98zm-11.553 0v-1.436c0-.54.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.435a.98.98 0 0 1-.977.98H34.97a.98.98 0 0 1-.976-.98zm0-8.63v-1.433c0-.54.437-.98.976-.98h1.438a.98.98 0 0 1 .977.98v1.433a.978.978 0 0 1-.977.977H34.97a.977.977 0 0 1-.976-.977zm23.153-11.135H2.683V8.901a3.005 3.005 0 0 1 3-3.002h5.401v2.264a3.404 3.404 0 0 0 6.807 0V5.9h24.048v2.264a3.404 3.404 0 0 0 6.806 0V5.9h5.397A3.007 3.007 0 0 1 57.147 8.9v10.152zm0 32.728a3.008 3.008 0 0 1-3.005 3.005H5.683c-1.655 0-3-1.348-3-3.005V21.413h54.464v30.368zM13.444 3.456c0-.576.465-1.044 1.041-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.041-1.042V3.456zm30.854 0c0-.576.466-1.044 1.042-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.042-1.042V3.456zm13.379 52.33c1.173-1.053 1.83-2.405 1.83-4.005V8.901a5.369 5.369 0 0 0-5.365-5.362h-5.397v-.083A3.409 3.409 0 0 0 45.34.053a3.406 3.406 0 0 0-3.401 3.403v.083H17.89v-.083A3.409 3.409 0 0 0 14.485.053a3.406 3.406 0 0 0-3.4 3.403v.083H5.682A5.368 5.368 0 0 0 .323 8.9v42.88c0 1.6.756 3.077 1.83 4.005 1.073.928 2.054 1.175 2.512 1.256.332.065.67.104 1.018.104h48.46c.35 0 .69-.04 1.017-.104.687-.168 1.344-.203 2.517-1.256z"/>
								</svg>
							</a>
						</div>
						<div class="cart">
							<a href="<?php echo wc_get_cart_url(); ?>" title="Cart" data-count="<?php echo $cart_count; ?>">
								<img src="<?php echo bloginfo( 'stylesheet_directory'); ?>/assets/white-cart.svg" alt="Cart">
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<div class="wrapper">

		<?php if( !is_front_page() ) : ?>
			<?php get_template_part( 'template', 'header' ); ?>
		<?php endif; ?>