<?php get_header(); ?>
	
		<article class="container" id="post-<?php the_ID(); ?>">

				<div <?php post_class(); ?>>

				<div class="wysiwyg">
					<?php the_field('testimonials_intro_copy', 'option'); ?>
				</div>
				<div class="container__main">
					<?php while( have_posts() ) : the_post(); ?>
						<div class="testimonial">
							<div class="quotes"></div>
							<div class="testimonial_content">
								<?php the_content(); ?>
							</div>
							<div class="testimonial__heading">
								<h2><?php echo $post->post_title; ?>
								<?php if( get_field('title', $post) ) : ?>
									<span> 
										<?php the_field('title', $post); ?>
									</span>
									</h2>
								<?php else : ?>
									</h2>
								<?php endif; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>

		</article>
		<footer class="archive-pagination">
			<?php
				echo paginate_links( array(
					'prev_text' => '<i class="fa fa-angle-left"></i>',
					'next_text' => '<i class="fa fa-angle-right"></i>',
					'type' => 'plain'
				) );
			?>
		</footer>

<?php get_footer();