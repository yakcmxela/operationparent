<?php
/*
 * Template Name: Contact
 */
get_header(); ?>

<?php
	$primary_location = get_posts(array(
		'post_type' => 'locations_module',
		'meta_key' => 'primary_location',
		'meta_value' => true
	))[0];

	$address_1 = get_field('street_address_line_1', $primary_location->ID);
	$address_2 = get_field('street_address_line_2', $primary_location->ID);
	$address_city = get_field('city', $primary_location->ID);
	$address_state = get_field('state_region', $primary_location->ID);
	$address_zip = get_field('zip_postal_code', $primary_location->ID);
	$address_country = get_field('country', $primary_location->ID);

	$contact_address = '';

	if($address_1){
		$contact_address .= $address_1 . '<br>';
	}
	if($address_2){
		$contact_address .= $address_2 . '<br>';
	}
	if($address_city){
		$contact_address .= $address_city . ', ';
	}
	if($address_state){
		$contact_address .= $address_state;
	}
	if($address_zip){
		$contact_address .= ' ' . $address_zip . '<br>';
	}
	if($address_country){
		$contact_address .= $address_country;
	}
	
	$phone = get_field('phone', $primary_location->ID);
	$fax = get_field('fax', $primary_location->ID);
	$email = get_field('email', $primary_location->ID);
	$contact_url = get_field('url', $primary_location->ID);
?>

	<div class="container">
		<?php while( have_posts() ): the_post(); ?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
				<?php the_content(); ?>
				<div class="contact-container">
					<div class="contact-page-content">
						<section class="contact-info">
							<?php if($phone){ ?>
								<p><span class="label">Phone:</span><br> 
									<a href="tel:<?php echo preg_replace('/\D+/', '', $phone); ?>">
										<?php echo $phone; ?>
									</a>
								</p>
							<?php } ?>
							<?php if($fax){ ?>
								<p><span class="label">Fax:<b</r> 
									<a href="tel:<?php echo preg_replace('/\D+/', '', $fax); ?>">
										<?php echo $fax; ?>
									</a>
								</p>
							<?php } ?>
							<?php if($email){ ?>
								<p><span class="label">Email:</span><br> 
									<a href="mailto:<?php echo $email; ?>">
										<?php echo $email; ?>
									</a>
								</p>
							<?php } ?>
							<?php 
							$locations = get_posts(array(
								'post_type' => 'locations_module',
								'orderby' => 'meta_value',
								'meta_key' => 'primary_location',
								'order' => 'DESC',
							));
							foreach ($locations as $location) :
							?>
							<p>
								<span class="label"><?php echo $location->post_title; ?></span><br>
								<?php if(get_field('street_address_line_1', $location->ID)) : ?>
									<?php echo get_field('street_address_line_1', $location) ?><br>
								<?php endif; ?>
								<?php if(get_field('street_address_line_2', $location->ID)) : ?>
									<?php echo get_field('street_address_line_2', $location) ?><br>
								<?php endif; ?>
								<?php if(get_field('city', $location->ID)) : ?>
									<?php echo get_field('city', $location) ?>, 
								<?php endif; ?>
								<?php if(get_field('state_region', $location->ID)) : ?>
									<?php echo get_field('state_region', $location) ?>
								<?php endif; ?>
								<?php if(get_field('zip_postal_code', $location->ID)) : ?>
									<?php echo get_field('zip_postal_code', $location) ?><br>
								<?php endif; ?>
								<?php if(get_field('custom_directions', $location->ID)) : ?>
									<span class="custom-directions"><?php echo get_field('custom_directions', $location->ID); ?></span>
								<?php endif; ?>
							</p>
							<?php endforeach; ?>
						</section>
						<?php if( get_field('display_hours', $primary_location->ID) == true ) : ?>
							<?php if(have_rows('hours', $primary_location->ID)): ?>
								<section class="contact-hours">
									<p><span class="label">Hours</span></p>
									<ul class="contact-hours-list">
										<?php while(have_rows('hours', $primary_location->ID)): the_row(); ?>
											<li><?php the_sub_field('hours'); ?></li>
										<?php endwhile; ?>
									</ul>
								</section>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<div class="contact-page-form">
						<?php echo do_shortcode('[gravityform id="1" title="true" description="false" ajax="false"]'); ?>
					</div>
				</div>
				
			</article>
		<?php endwhile; ?>
	</div>

<?php get_footer();