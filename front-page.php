<?php get_header(); ?>
	<?php 
		if( have_rows('page_layout') ) :
			while( have_rows('page_layout') ) : the_row();
				if( get_row_layout() == 'hero' ) : 
					$image = get_sub_field('image');
					$heading = get_sub_field('heading');
					if( preg_match('/No One Said/', $heading) ) :
						$heading = str_replace('No One Said', 'No One Said<br>', $heading);
					endif;
					$subheading = get_sub_field('subheading');
					$link_to = get_the_permalink( get_sub_field('link_to') );
					$link_text = get_sub_field('link_text') ?: get_the_title( get_sub_field('link_to') );
					?>
					<section class="hero">
						<div class="hero__image" style="background-image: url('<?php echo $image['url']; ?>');">
							<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
						</div>
						<div class="hero__content">
							<h1 class="hero__heading"><?php echo $heading; ?></h1>
							<?php echo $subheading; ?>
							<a class="button" href="<?php echo $link_to; ?>" title="<?php echo $link_text; ?>">
								<?php echo $link_text; ?>
							</a>
						</div>
					</section>
					<?php
				elseif( get_row_layout() == 'link_blocks' ) :
					?>
					<section class="link-blocks">
						<div class="container">
							<h2 class="link-blocks__heading"><?php the_sub_field('heading'); ?></h2>
							<?php the_sub_field('subheading'); ?>
							<?php if( have_rows('link_block') ) : ?>
								<div class="flex-columns-3">
								<?php 
									while( have_rows('link_block') ) : the_row(); 
										$image = get_sub_field('image');
										if(!$image) :
											$image_url = get_the_post_thumbnail_url( get_sub_field('link_to') );
											$image_alt = get_post_meta( $image_url, '_wp_attachment_image_alt', true );
											$image = array(
												'url' => $image_url,
												'alt' => $image_alt,
											);
										endif;
										$link_to = get_the_permalink( get_sub_field('link_to') );
										$link_title = get_sub_field('block_title') ?: get_the_title( get_sub_field('link_to') );
										$wayfinder_color = strtolower( get_field( 'wayfinder_color', get_sub_field('link_to') ) );
									?>
									<div class="link-blocks__block block <?php echo $wayfinder_color; ?>">
										<a href="<?php echo $link_to; ?>" title="<?php echo $link_title; ?>">
											<div class="link-blocks__image">
												<img src="<?php echo $image['url'] ?>" alt="<?php echo $image['alt']; ?>">
											</div>
											<div class="link-blocks__title">
												<h3><?php echo $link_title; ?></h3>
											</div>
										</a>
									</div>
								<?php endwhile; ?>
								</div>
							<?php endif; ?>
						</div>
					</section>
					<?php 
				elseif( get_row_layout() == 'content_blocks' ) : 
					?>
					<section class="content-blocks">
						<div class="container">
							<h2 class="content-blocks__heading"><?php the_sub_field('heading'); ?></h2>
							<?php the_sub_field('subheading'); ?>
								<?php if( have_rows('content_block') ) : ?>
									<div class="columns-4">
									<?php 
										while( have_rows('content_block') ) : the_row();
											$icon = get_sub_field('icon');
											$superscript = get_sub_field('superscript');
											$block_title = get_sub_field('block_title');
											$block_content = get_sub_field('block_content');
											$link_to = get_the_permalink( get_sub_field('link_to') );
											$link_text = get_sub_field('link_text') ?: get_the_title( get_sub_field('link_to') );
											$anchor = get_sub_field('anchor') ?: '';
										?>
										<div class="content-blocks__block block">
											<a href="<?php echo $link_to; ?><?php echo $anchor; ?>" title="<?php echo $link_title; ?>"> 
												<div class="content-blocks__icon">
													<img src="<?php echo $icon['url'] ?>" alt="<?php echo $link_text; ?>">
												</div>
												<div class="content-blocks__container">
													<div class="content-blocks__title">
														<h3>
															<?php if($superscript) : ?>
																<span><?php echo $superscript; ?></span>
															<?php endif; ?>
															<?php if($block_title) : ?>
																<span><?php echo $block_title; ?></span>
															<?php endif; ?>
														</h3>
													</div>
													<div class="content-blocks__content">
														<div class="content-blocks__button">
															<div class="button">
																<?php echo $link_text; ?>
															</div>
														</div>
														<div class="content-blocks__text">
															<?php if($block_content) : ?>
																<?php echo $block_content; ?>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</a>
										</div>
									<?php endwhile; ?>
									</div>
								<?php endif; ?>
						</div>
					</section>
					<?php
				elseif( get_row_layout() == 'split_block' ) : 
					?>
					<section class="split-block">
						<div class="split-block__featured-post">
							<?php
								$featured_post = get_sub_field('featured_post');
								$heading_left = str_replace(html_entity_decode(get_the_title($featured_post), ENT_QUOTES), '<span>'.get_the_title($featured_post).'</span>', get_sub_field('heading_left'));
								$text_content_left = get_sub_field('text_content_left');
								$image = get_sub_field('image');
								$excerpt = get_sub_field('excerpt');
								$link_to_left = get_the_permalink($featured_post->ID);
								$link_text_left = get_sub_field('link_text_left') ?: get_the_title($featured_post);

							?>
							<div class="split-block__featured-post-heading">
								<h2><?php echo $heading_left; ?></h2>
								<?php echo $text_content_left; ?>
							</div>
							<div class="split-block__featured-post-content">
								<?php if($image) : ?>
									<div class="split-block__featured-post-image">
										<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
									</div>
								<?php endif; ?>
								<?php if($excerpt) : ?>
									<div class="split-block__featured-post-excerpt">
										<?php echo $excerpt; ?>
									</div>
								<?php else : ?>
									<div class="split-block__featured-post-excerpt">
										<?php echo wp_trim_words($featured_post->post_excerpt); ?>
									</div>
								<?php endif; ?>
							</div>
							<a class="button" href="<?php echo $link_to_left; ?>" title="<?php echo $link_text_left; ?>">
								<?php echo $link_text_left; ?>
							</a>
						</div>
						<div class="split-block__featured-products">
							<?php
								$shop_page_title = get_the_title(wc_get_page_id( 'shop' ));
								$collection_type = get_sub_field('collection_type');
								$heading_right = str_replace(html_entity_decode($shop_page_title, ENT_QUOTES), '<span>'.$shop_page_title.'</span>', get_sub_field('heading_right'));
								$text_content_right = get_sub_field('text_content_right');
								$link_text_right = get_sub_field('link_text_right') ?: 'Shop All Products';
								?>
								<div class="split-block__featured-products-heading">
									<h2><?php echo $heading_right; ?></h2>
									<?php echo $text_content_right; ?>
								</div>
								<?php
								if($collection_type == 'product_cat') :
									$args = array(
										'posts_per_page' => 2,
										'category' => array( get_sub_field('product_category')->slug ),
										'order' => 'DESC',
										'orderby' => 'menu_order'
									);
									$products = wc_get_products( $args ); 
									$count = count($products);
									?>
									<div class="columns-<?php echo $count; ?>">
										<?php foreach ($products as $product) : ?>
											<div class="split-block__featured-product product">
												<div class="split-block__product-image">
													<a href="<?php $product->get_permalink(); ?>">
														<?php echo $product->get_image(); ?>
													</a>
												</div>
												<div class="split-block__product-content">
													<h3><?php echo $product->get_title(); ?></h3>
													<?php 
														$type = $product->get_type();
														if($type == 'variable') :
															echo '<span>$' . $product->get_variation_price('min') . '</span>';
														else :
															echo $product->get_price_html();
														endif;
													?>
													<?php 
														if($type == 'simple') :
															$atc_icon = '<img src="' . get_bloginfo('stylesheet_directory') . '/assets/white-cart.svg">';
															echo prepare_ajax_atc($product, 'add-to-cart', $atc_icon, 'simple'); 
														endif;
													?>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
									<?php
								elseif($collection_type == 'curated') :
									?>
									<?php if( have_rows('content' ) ) : ?>
										<div class="columns-2">
											<?php while( have_rows('content') ) : the_row(); ?>
												<?php $content_type = get_sub_field('content_type'); ?>
												<?php 
													$classes = '';
													$target = '';
													if($content_type == 'product') :
														$product = wc_get_product(get_sub_field('product')->ID); 
														$permalink = $product->get_permalink();
														$image = $product->get_image();
														$title = $product->get_title();
													elseif ($content_type == 'post') : 
														$permalink = get_the_permalink( get_sub_field('post_page')->ID );
														$image = get_the_post_thumbnail( get_sub_field('post_page')->ID );
														$title = get_the_title( get_sub_field('post_page')->ID );
														$classes = 'no-padding';
													elseif($content_type == 'external') :
														$permalink = get_sub_field('external_link');
														$image = '<img src="' . get_sub_field('external_link_image')['url'] . '">';
														$title = get_sub_field('external_link_title');
														$target = '_blank';
														$classes = 'no-padding';
													endif;
												?>
												<div class="split-block__featured-product product">
													<div class="split-block__product-image <?php echo $classes; ?>">
														<a target="<?php echo $target; ?>" href="<?php echo $permalink; ?>">
															<?php echo $image; ?>
														</a>
													</div>
													<div class="split-block__product-content">
														<a target="<?php echo $target; ?>" href="<?php echo $permalink; ?>">
															<h3><?php echo $title; ?></h3>
															<?php if( get_field('edition', get_sub_field('product') ) && $content_type == 'product' ) : ?>
																<span><?php echo get_field('edition', get_sub_field('product')); ?>
															<?php endif; ?>
														</a>

														<?php if( $content_type == 'product' ) : ?>
															<div class="price">
																<?php 
																	$type = $product->get_type();
																	if($type == 'variable') :
																		echo '<span>$' . $product->get_variation_price('min') . '</span>';
																	else :
																		echo $product->get_price_html();
																	endif;
																?>
																<?php 
																	if($type == 'simple') :
																		$atc_icon = '<img src="' . get_bloginfo('stylesheet_directory') . '/assets/white-cart.svg">';
																		echo prepare_ajax_atc($product, 'add-to-cart', $atc_icon, 'simple'); 
																	endif;
																?>
															</div>
														<?php endif; ?>
													</div>
												</div>
											<?php endwhile; ?>
										</div>
									<?php endif; ?>
									<?php
								endif;
							?>
							<div class="split-block__link">
								<?php if( get_sub_field('link_to_right') ) : ?>
									<?php $link_text_right = get_sub_field('link_text_right') ? get_sub_field('link_text_right') : get_the_title( get_sub_field('link_to_right')->ID ); ?>
									<a class="button white" href="<?php echo get_permalink( get_sub_field('link_to_right')->ID ); ?>" title="<?php echo $link_text_right; ?>"><?php echo $link_text_right; ?></a>
								<?php endif; ?>
							</div>
						</div>
					</section>
					<?php
				elseif( get_row_layout() == 'featured_event') : 
					$event = get_sub_field('event');
					$time_format = get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT );
					$time_range_separator = ' - ';

					$start_datetime = tribe_get_start_date();
					$start_date = tribe_get_start_date( $event->ID, false );
					$start_time = tribe_get_start_date( $event->ID, false, $time_format );
					$start_ts = tribe_get_start_date( $event->ID, false, Tribe__Date_Utils::DBDATEFORMAT );

					$end_datetime = tribe_get_end_date();
					$end_date = tribe_get_display_end_date( $event->ID, false );
					$end_time = tribe_get_end_date( $event->ID, false, $time_format );
					$end_ts = tribe_get_end_date( $event->ID, false, Tribe__Date_Utils::DBDATEFORMAT );

					$time_formatted = null;
					if ( $start_time == $end_time ) {
						$time_formatted = esc_html( $start_time );
					} else {
						$time_formatted = esc_html( $start_time . $time_range_separator . $end_time );
					}
					$background_image = get_sub_field('background_image')['url'] ?: get_the_post_thumbnail_url($event->ID);
					?>
					<section class="featured-event" style="background-image: url(<?php echo $background_image; ?>);">
						<div class="overlay" style="background-color: <?php echo get_sub_field('overlay_color'); ?>; opacity: <?php echo (get_sub_field('overlay_opacity') / 100 ); ?>"></div>
						<div class="container">
							<svg xmlns="http://www.w3.org/2000/svg" width="60" height="58" viewBox="0 0 60 58">
							    <path fill="#FFF" fill-rule="evenodd" d="M22.444 47.445v-1.436c0-.539.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.436a.976.976 0 0 1-.977.979h-1.433a.98.98 0 0 1-.977-.98zM10.891 30.188v-1.433c0-.54.438-.98.977-.98h1.437c.54 0 .978.44.978.98v1.433a.978.978 0 0 1-.978.977h-1.437a.977.977 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.436a.98.98 0 0 1-.977.979H34.97a.98.98 0 0 1-.976-.98zm-11.55-8.628v-1.435c0-.54.438-.977.977-.977h1.433c.544 0 .977.438.977.977v1.435c0 .54-.433.98-.977.98h-1.433a.98.98 0 0 1-.977-.98zm-11.553 8.628v-1.436c0-.539.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.436a.98.98 0 0 1-.978.979h-1.437a.979.979 0 0 1-.977-.98zm0-8.628v-1.435c0-.54.438-.977.977-.977h1.437c.54 0 .978.438.978.977v1.435a.98.98 0 0 1-.978.98h-1.437a.98.98 0 0 1-.977-.98zm11.553-8.629v-1.433c0-.54.438-.98.977-.98h1.433c.544 0 .977.44.977.98v1.433a.975.975 0 0 1-.977.977h-1.433a.978.978 0 0 1-.977-.977zm23.103 17.257v-1.436c0-.539.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.436a.98.98 0 0 1-.976.979h-1.434a.98.98 0 0 1-.977-.98zm0-17.257v-1.433c0-.54.438-.98.977-.98h1.434a.98.98 0 0 1 .976.98v1.433a.978.978 0 0 1-.976.977h-1.434a.978.978 0 0 1-.977-.977zm0 8.63v-1.436c0-.54.438-.977.977-.977h1.434c.538 0 .976.438.976.977v1.435a.98.98 0 0 1-.976.98h-1.434a.98.98 0 0 1-.977-.98zm-11.553 0v-1.436c0-.54.437-.977.976-.977h1.438c.54 0 .977.438.977.977v1.435a.98.98 0 0 1-.977.98H34.97a.98.98 0 0 1-.976-.98zm0-8.63v-1.433c0-.54.437-.98.976-.98h1.438a.98.98 0 0 1 .977.98v1.433a.978.978 0 0 1-.977.977H34.97a.977.977 0 0 1-.976-.977zm23.153-11.135H2.683V8.901a3.005 3.005 0 0 1 3-3.002h5.401v2.264a3.404 3.404 0 0 0 6.807 0V5.9h24.048v2.264a3.404 3.404 0 0 0 6.806 0V5.9h5.397A3.007 3.007 0 0 1 57.147 8.9v10.152zm0 32.728a3.008 3.008 0 0 1-3.005 3.005H5.683c-1.655 0-3-1.348-3-3.005V21.413h54.464v30.368zM13.444 3.456c0-.576.465-1.044 1.041-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.041-1.042V3.456zm30.854 0c0-.576.466-1.044 1.042-1.044.576 0 1.046.468 1.046 1.044v4.707c0 .574-.47 1.042-1.046 1.042a1.042 1.042 0 0 1-1.042-1.042V3.456zm13.379 52.33c1.173-1.053 1.83-2.405 1.83-4.005V8.901a5.369 5.369 0 0 0-5.365-5.362h-5.397v-.083A3.409 3.409 0 0 0 45.34.053a3.406 3.406 0 0 0-3.401 3.403v.083H17.89v-.083A3.409 3.409 0 0 0 14.485.053a3.406 3.406 0 0 0-3.4 3.403v.083H5.682A5.368 5.368 0 0 0 .323 8.9v42.88c0 1.6.756 3.077 1.83 4.005 1.073.928 2.054 1.175 2.512 1.256.332.065.67.104 1.018.104h48.46c.35 0 .69-.04 1.017-.104.687-.168 1.344-.203 2.517-1.256z"/>
							</svg>
							<div class="featured-event__event">
								<h2><?php echo $event->post_title; ?></h2>
								<ul class="featured-event__meta">
									<li><?php esc_html_e( $start_date ); ?></li>
									<li><?php echo $time_formatted; ?></li>
									<li><?php echo tribe_get_full_address( $event->ID ); ?></li>
								</ul>
								<?php echo tribe_events_get_the_excerpt( $event->ID, wp_kses_allowed_html( 'post' ) ); ?>
								<a class="button button__white" href="<?php echo get_the_permalink($event->ID); ?>">Learn More</a>
							</div>
						</div>
					</section>
					<?php
				endif;
			endwhile;
		endif;
	?>
<?php get_footer();