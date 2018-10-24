<?php 
$queried_object = get_queried_object();
if( get_field('toggle_related_posts', $post) ) :
	if( get_field('related_post_type', $post) == 'key_issue' ) : ?>
		<div class="footer__key-issues container">
			<?php if( have_rows('related_key_issues', $post) ) : ?>
				<?php while( have_rows('related_key_issues', $post) ) : the_row(); ?>
					<?php $child = get_sub_field('post'); ?>
					<div class="ki__section <?php echo strtolower(get_field('wayfinder_color', $child)); ?> light">
						<div class="image" style="background-image: url('<?php echo get_the_post_thumbnail_url( $child );?>');">
						</div>
						<div class="content">
							<h2><?php echo $child->post_title; ?></h2>
							<a class="button" href="<?php echo get_the_permalink($child->ID); ?>" title="<?php echo $child->post_title; ?>">
								Learn More
							</a>
						</div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<div class="footer__related-posts">
			<div class="container">
			<?php if( get_field('related_content', $post) ) : ?>
				<?php the_field('related_content', $post); ?>
			<?php endif; ?>
			<?php if( have_rows('related_posts', $post) ) : ?>
				<div class="columns-4">
				<?php while( have_rows('related_posts', $post) ) : the_row(); ?>
					<?php $post_obj = get_sub_field('post');?>
					<div class="footer__featured-<?php echo $post_obj->post_type; ?> <?php echo $post_obj->post_type; ?>">
						<?php if($post_obj->post_type == 'product') : ?>
							<?php $product = wc_get_product($post_obj->ID); ?>
							<div class="footer__product-image">
								<a href="<?php echo $product->get_permalink(); ?>">
									<?php echo $product->get_image(); ?>
								</a>
							</div>
							<div class="footer__product-content">
								<a href="<?php echo $product->get_permalink(); ?>">
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
								</a>
							</div>
						<?php elseif($post_obj->post_type == 'resource_manager') : ?>
							<?php 
								$resource = get_post( $post_obj->ID );
								$resource_type = get_field('resource_type', $resource );
								if($resource_type == 'video_embed') :
									if( get_field('video_embed', $resource) ) : 
									?>
									<div class="footer__resource-embed">
										<?php the_field('video_embed', $resource) ?>
									</div>
									<?php endif; ?>
								<?php else : ?>
								<a target="_blank" href="<?php echo get_field('resource_file', $resource) ?>" download>
									<div class="footer__resource-image">
										<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/download-icon.png">
									</div>
								</a>
							<?php endif; ?>
							<div class="footer__resource-labels">
								<p class="footer__resource-name">
									<?php if($resource_type == 'video_embed') : ?>
										<span class="video-embed-link">
											<?php echo $resource->post_title; ?>
										</span>
									<?php else : ?>
										<?php if(get_field('resource_file', $resource) ) : ?>
										<a target="_blank" href="<?php echo get_field('resource_file') ?>" download>
										<?php endif; ?>
											<?php the_title(); ?>
										<?php if(get_field('resource_file', $resource) ) : ?>
										</a>
										<?php endif; ?>
									<?php endif; ?>
								</p>
								<?php $terms = get_the_terms( $resource->ID, 'resource_type' ); ?>
								<?php if($terms) : ?>
								<p>Resource Tag
								<?php endif; ?>
								<?php
									foreach($terms as $term) :
										$term_link = esc_url(get_term_link( $term->term_id));
								?>
									<span class="term">
										[<a href="<?php echo $term_link; ?>" title="<?php echo $term->name; ?>">
											<?php echo $term->name; ?>
										</a>]
									</span>
								<?php endforeach; ?>
								<?php if($terms) : ?>
								</p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; ?>
				</div>
			<?php endif; ?>
			<?php if(get_field('related_link_to', $post) ) : ?>
				<div class="see-more">
					<?php $link_text = get_field('related_link_text', $post) ?: 'Learn More'; ?>
					<a class="button see-more" href="<?php echo get_the_permalink(get_field('related_link_to', $post) ); ?>" >
						<?php echo $link_text; ?>
					</a>
				</div>
			<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>