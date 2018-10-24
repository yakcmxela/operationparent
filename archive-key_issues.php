<?php get_header(); ?>
	
		<article class="container" id="post-<?php the_ID(); ?>">

				<div <?php post_class(); ?>>

				<div class="wysiwyg">
					<?php the_field('key_issues_content', 'option'); ?>
				</div>

				<?php 
					$args = array(
						'posts_per_page' => -1,
						'post_type' => 'key_issues',
						'orderby' => 'meta_value_num',
						'meta_key' => 'menu_order',
						'order' => 'ASC',
					);
					$children = get_posts( $args );
					foreach ($children as $child) :
						$wayfinder_color = strtolower(get_field('wayfinder_color', $child));
						?>
						<div class="ki__section <?php echo $wayfinder_color; ?> light">
							<div class="image" style="background-image: url('<?php echo get_the_post_thumbnail_url( $child );?>');">
								
							</div>
							<div class="content">
								<h2><?php echo $child->post_title; ?></h2>
								<p><?php echo get_field('overview_page_excerpt', $child); ?></p>
								<a class="button" href="<?php echo get_the_permalink($child->ID); ?>" title="<?php echo $child->post_title; ?>">
									Learn More
								</a>
							</div>
						</div>
					<?php endforeach; ?>
			</div>

		</article>

<?php get_footer();