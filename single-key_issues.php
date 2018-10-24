<?php get_header(); ?>
	
	<?php while( have_posts() ): the_post(); ?>

		<article id="post-<?php the_ID(); ?>">

			<div <?php post_class(); ?>>

				<div class="container">
					
					<div class="wysiwyg">
						<?php the_content(); ?>
					</div>

				</div>

				<div class="key-points">
					<?php if( have_rows('key_points') ) : ?>
						<?php while( have_rows('key_points') ) : the_row(); ?>
							<div class="key-points__point <?php echo strtolower(get_field('wayfinder_color')); ?>">
								<div class="container">
									<div class="content">
										<h2><?php the_sub_field('heading'); ?></h2>
										<p><?php the_sub_field('content'); ?></p>
									</div>
									<?php $class = ''; ?>
									<?php $icon = null; ?>
									<?php if( get_sub_field('fact') && get_sub_field('add_fact') == true ) : ?>
										<?php $class = ' has-fact'; ?>
										<?php $icon = '<div class="icon"></div>'; ?>
									<?php endif; ?>
									<div class="image<?php echo $class ?>" style="background-image: url('<?php echo get_sub_field('image')['url'] ?>');">
										<?php if( $icon !== null ) : ?>
											<?php echo $icon; ?>
										<?php endif; ?>
										<?php if( get_sub_field('fact') ) : ?>
											<div class="fact">
												<?php the_sub_field('fact'); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					<?php endif; ?>
				</div>

			</div>

		</article>

	<?php endwhile; ?>

<?php get_footer();