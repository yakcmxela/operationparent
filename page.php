<?php get_header(); ?>
	
	<?php while( have_posts() ): the_post(); ?>

		<article class="container" id="post-<?php the_ID(); ?>">
			
			<?php if(get_queried_object()->name !== 'tribe_events') : ?>
				<div class="container__main">
			<?php endif; ?>

				<div <?php post_class(); ?>>

					<div class="wysiwyg">
						<?php the_content(); ?>
					</div>

				</div>

			<?php if(get_queried_object()->name !== 'tribe_events') : ?>
				<div class="container__main">
			<?php endif; ?>

		</article>

	<?php endwhile; ?>

<?php get_footer();