<?php get_header(); ?>

	<div class="container">
		<div class="msw-case-studies-filter">
			<div class="filter-container filter-container-services">
				<div class="filter-label">Filter By: </div>
				<div class="filter-dropdown">
					<div class="filter-display">
						<?php
							if( single_term_title( '', false ) ){
								single_term_title();
							} else {
								echo 'Category';
							}
						?>
					</div>
					<ul>
						<li><a href="<?php echo get_post_type_archive_link( 'resource_manager' ); ?>">All</a></li>
						<?php
							$categories = get_terms( array(
								'orderby' => 'name',
								'order'   => 'ASC',
								'taxonomy' => 'resource_type'
							) );
							foreach( $categories as $category ) {
								$caturl = get_term_link( $category->term_id );
								$catname = $category->name;
								echo '<li><a href="' . $caturl .'">' . $catname. '</a></li>';
							}
						?>
					</ul>
				</div>
			</div>
		</div>
		<ol class="msw-resource-list columns-4">
			<?php while( have_posts() ) : the_post(); ?>
				<?php $resource_type = get_field('resource_type'); ?>
				<li class="msw-resource-item block">
					<?php if($resource_type == 'video_embed') : ?>
						<?php if( get_field('video_embed') ) : ?>
							<div class="msw-resource-embed">
								<?php the_field('video_embed') ?>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<a target="_blank" href="<?php echo get_field('resource_file') ?>" download>
							<div class="msw-resource-image">
								<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/assets/download-icon.png">
							</div>
						</a>
					<?php endif; ?>
					<div class="msw-resource-labels">
						<p class="msw-resource-name">
							<?php if($resource_type == 'video_embed') : ?>
								<span class="video-embed-link">
									<?php the_title(); ?>
								</span>
							<?php else : ?>
								<?php if(get_field('resource_file') ) : ?>
								<a target="_blank" href="<?php echo get_field('resource_file') ?>" download>
								<?php endif; ?>
									<?php the_title(); ?>
								<?php if(get_field('resource_file') ) : ?>
								</a>
								<?php endif; ?>
							<?php endif; ?>
						</p>
						<?php $terms = get_the_terms( get_the_ID(), 'resource_type' ); ?>
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
				</li>
			<?php endwhile; ?>
		</ol>
		<footer class="archive-pagination">
			<?php
				echo paginate_links( array(
					'prev_text' => '<i class="fa fa-angle-left"></i>',
					'next_text' => '<i class="fa fa-angle-right"></i>',
					'type' => 'plain'
				) );
			?>
		</footer>
	</div>

<?php get_footer();