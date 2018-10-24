<?php get_header(); ?>

	<div class="container">
		<div class="intro-content">
			<?php if(!is_search()) : ?>
				<div class="content">
					<?php the_field('content', get_option( 'page_for_posts' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="filter-container">
				<div class="filter-label">Filter By:</div>
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
						<li><a href="<?php echo get_permalink( get_option('page_for_posts' ) ); ?>">All</a></li>
						<?php
							$categories = get_categories( array(
								'orderby' => 'name',
								'order'   => 'ASC'
							) );

							foreach( $categories as $category ) {
								$caturl = get_category_link( $category->term_id );
								$catname = $category->name;

								echo '<li><a href="' . $caturl .'">' . $catname. '</a></li>';
							}
						?>
					</ul>
				</div>
			</div>
		</div>
		<?php
		/*
			.archive-list has 3 variation classes:
			
			.full-width
				Each article is 100% width.
				Image and content are stacked, also 100% width.
			
			.half-col
				Each article is 50% width.
				Image and content are stacked, 100% of the article width.
			
			.alternating-cols
				All articles are 100% width.
				Image is 25% width, content is 75%.
				Odd articles have image on left, content on right, text aligned left.
				Even articles have image on right, content on left, text aligned right.
				
		*/
		?>
		<div class="archive-list full-width">
			<?php while( have_posts() ): the_post(); ?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<?php
						$thumb_image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
						$thumb_class = '';
						if(!$thumb_image) :
							$thumb_image = get_bloginfo('stylesheet_directory') . '/assets/white-chat-icons.png';
							$thumb_class = 'placeholder';
						endif;
					?>
					
					<figure class="post-thumbnail <?php echo $default_image; ?>">
						<a href="<?php the_permalink(); ?>" class="img <?php echo $thumb_class; ?>" style="background-image: url(<?php echo $thumb_image; ?>)"></a>
					</figure>
					<div class="content">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						<ul class="post-meta">
							<li><?php the_time( 'F j, Y' ); ?></li>
							<li><?php read_time(); ?></li>
						</ul>
						<?php the_excerpt(); ?>
						<div class="categories">
							<ul>
							<?php 
								$categories = get_the_category(); 
								foreach($categories as $category) :
									$category_link = esc_url(get_category_link( $category->term_id));
							?>
								<li>
									<a href="<?php echo $category_link; ?>" title="<?php echo $category->name; ?>">
										<?php echo $category->name; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		
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