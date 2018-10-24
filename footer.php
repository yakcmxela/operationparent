		</div><!-- /.wrapper -->
		<?php get_template_part( 'template', 'related-posts') ?>
		<?php get_template_part( 'template', 'footer-callout' ); ?>
		<?php 
			$alternate_logo = get_field('alternate_logo', 'option'); 
			$primary_location_args = array(
				'meta_key' => 'primary_location',
				'numberposts' => 1,
				'orderby' => 'meta_value',
				'post_type' => 'locations_module',
			);
			$primary_location = get_posts($primary_location_args)[0];
			wp_reset_postdata();
		?>
		<footer class="site-footer">
			<div class="site-footer__upper">
				<div class="site-footer__brand">
					<a href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
						<img src="<?php echo $alternate_logo['url'] ?>" alt="<?php echo $alternate_logo['alt']; ?>">
					</a>
					<h2>Follow Us</h2>
					<div class="social">
						<?php if( have_rows('social_media_links', $primary_location ) ) : ?>
							<?php while( have_rows('social_media_links', $primary_location ) ) : the_row(); ?>
								<a class="social-link" target="_blank" href="<?php the_sub_field('link'); ?>">
									<i class="fa fa-<?php the_sub_field('icon_or_image'); ?>"></i>
								</a>
							<?php endwhile; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="site-footer__location">
					<h2>Location</h2>
					<?php 
						$location_args = array(
							'numberposts' => 2,
							'orderby' => 'meta_value',
							'order' => 'ASC',
							'post_type' => 'locations_module',
						);
						$locations = get_posts( $location_args );
						foreach ($locations as $location) :
							$street1 = get_field( 'street_address_line_1', $location );
							$street2 = get_field( 'street_address_line_2', $location );
							$city = get_field( 'city', $location );
							$state = get_field( 'state_region', $location );
							$zip = get_field( 'zip_postal_code', $location );
							$phone = get_field( 'phone', $location );
							$email = get_field( 'email', $location );
							$google_map_details = get_field( 'google_map', $location );
							$google_maps_link = get_field( 'google_maps_link', $location );
						?>
							<div class="location">
								<h3><?php echo $location->post_title; ?></h3>
								<?php if($street1) : ?>
									<p><?php echo $street1; ?></p>
								<?php endif; ?>
								<?php if($street2) : ?>
									<p><?php echo $street2; ?></p>
								<?php endif; ?>
								<div class="city-state-zip">
									<?php if($city) : ?>
										<p><?php echo $city; ?>, </p>
									<?php endif; ?>
									<?php if($state) : ?>
										<p><?php echo $state; ?> </p>
									<?php endif; ?>
									<?php if($zip) : ?>
										<p><?php echo $zip; ?></p>
									<?php endif; ?>
								</div>
								<?php if($google_maps_link) : ?>
									<p><a href="<?php echo $google_maps_link; ?>" target="_blank">Get Directions</a></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
				</div>
				<div class="site-footer__contact">
					<h2>Contact Us</h2>
					<?php if( get_field('phone', $primary_location) ) : ?>
						<p class="label">Phone</p>
						<p>
							<a href="tel:<?php echo preg_replace('/\D+/', '', get_field('phone', $primary_location) ); ?>">
								<?php the_field('phone', $primary_location); ?>
							</a>
						</p>
					<?php endif; ?>
					<?php if( get_field('email', $primary_location) ) : ?>
						<p class="label">Email</p>
						<p>
							<a href="mailto:<?php the_field('email', $primary_location); ?>">
								<?php the_field('email', $primary_location); ?>
							</a>
						</p>
					<?php endif; ?>
					<?php if( get_field('newsletter_signup_shortcode', 'option') ) : ?>
						<div class="signup-link">
							<?php echo do_shortcode('[mc4wp_form id="' . get_field('newsletter_signup_shortcode', 'option') . '"]'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="site-footer__menu">
				<nav class="footer-inlinks">
					<p class="copyright">&copy;<?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>.</p>
					<ul>
						<li><a href="<?php echo home_url( 'privacy-policy' ); ?>" title="Privacy Policy">Privacy Policy</a></li>
						<li><a href="<?php echo home_url( 'site-info' ); ?>" title="Site Info">Site Info</a></li>
						<li><a href="<?php echo home_url( 'site-map' ); ?>" title="Site Map">Site Map</a></li>
						<?php if( have_rows('additional_footer_links', 'option') ) : ?>
							<?php while( have_rows('additional_footer_links', 'option') ) : the_row(); ?>
								<?php 
									$link_to = get_the_permalink(get_sub_field('link_to', 'option')->ID);
									$link_text = get_sub_field('link_text', 'option');
								?>
								<li><a href="<?php echo $link_to; ?>" title="<?php echo $link_text; ?>"><?php echo $link_text; ?></a></li>
							<?php endwhile; ?>
						<?php endif; ?>
					</ul>
				</nav>
				<div class="site-footer__attribution">
				<a href="https://www.makespaceweb.com" title="Louisville Web Design" target="_blank" id="makespace-bee" class="bee-color bee-flutter" <?php if( !is_front_page() ): ?>rel="nofollow" <?php endif ?>>
					<span class="makespace-bee-group">
						<span class="makespace-bee-body">
							<svg xmlns="http://www.w3.org/2000/svg" width="42.9" height="81.1" viewBox="0 0 42.9 81.1" preserveAspectRatio="xMinYMin meet">
							<path class="makespace-bee-head makespace-orange" d="M7.2,32.2c-1.4,0-2.8-0.6-3.8-1.6S1.9,28.2,2,26.7c0.1-2,0.2-4.2,0.3-5.7l0.1-0.8c0.1-0.6,0.2-1.9,1.2-2.9	C4.5,16.4,17.7,6.4,21.5,6.4c3.8,0,17,10,17.9,10.9c1.1,1,1.2,2.3,1.3,2.9l0.1,0.8c0.1,1.5,0.2,3.8,0.3,5.7c0.1,1.4-0.4,2.8-1.4,3.9
								c-1,1-2.4,1.6-3.8,1.6H7.2z"/>
							<path class="makespace-bee-body-1 makespace-blue" d="M1.5,42.5v-5c0-2.9,2.4-5.2,5.3-5.2h29.4c2.9,0,5.2,2.3,5.3,5.2v5H1.5z"/>
							<path class="makespace-bee-body-2 makespace-red" d="M1.7,52.7L1.5,42.5h39.9l-0.1,10.2H1.7z"/>
							<path class="makespace-bee-body-3 makespace-green" d="M1.9,62.9L1.7,52.7h39.6l-0.2,10.2H1.9z"/>
							<path class="makespace-bee-body-4 makespace-purple" d="M20.6,78.5C18,77,4.8,69.4,3.2,67.9c-1.2-1.1-1.3-2.6-1.3-3.1V63l1.8-0.1h37.4L41,64.7c0,0.6-0.1,2.1-1.3,3.2
								C38.2,69.4,25,77,22.4,78.5L21.5,79L20.6,78.5z"/>
							<path class="makespace-bee-body-outline makespace-brown" d="M3.3,40.7v-3.3c0-1.9,1.6-3.4,3.5-3.4h29.4c1.9,0,3.5,1.6,3.5,3.5v3.2C39.7,40.7,3.3,40.7,3.3,40.7z M4,21.2l0.1-0.7
								c0-0.4,0-1.3,0.7-2C6.3,17.1,18.7,8.1,21.5,8.1s15.2,9,16.7,10.4c0.7,0.7,0.7,1.6,0.8,2l0.1,0.7c0.1,1.4,0.2,3.7,0.3,5.6
								c0.1,2-1.5,3.7-3.5,3.7H7.2c-2,0-3.6-1.7-3.5-3.7C3.8,24.9,3.9,22.7,4,21.2z M0,58.1l0.1,3.1v3.6c0.1,1.9,0.7,3.4,1.8,4.4
								c1.6,1.5,11.8,7.4,17.7,10.9l1.7,1l1.7-1c5.9-3.4,16.2-9.4,17.7-10.9c1.2-1.1,1.8-2.6,1.8-4.4l0.1-3.6V58c0,0,0.2-10.2,0.2-11.9
								s0.1-17.9,0.1-20.1s-0.5-6-0.5-6c-0.1-0.7-0.3-2.5-1.8-4c-0.1-0.1-3.6-2.9-7.7-5.7V5.7l3,2C36.8,8.3,38,8,38.6,7.1S39,4.9,38,4.3
								l-6.1-4c-0.6-0.4-1.4-0.4-2-0.1s-1.1,1-1.1,1.8v5.8C26,6,23.2,4.7,21.5,4.7S16.9,6.1,14,7.9V2c0-0.7-0.4-1.4-1.1-1.8
								c-0.6-0.3-1.4-0.3-2,0.1l-6.1,4C3.9,4.9,3.6,6.1,4.2,7.1C4.8,8,6,8.3,7,7.7l3-2v4.8c-3.9,2.7-7.3,5.4-7.6,5.6
								c-1.5,1.5-1.7,3.3-1.8,4c0,0-0.5,2.9-0.5,5.5S0,47.8,0,47.8L0,58.1z M38.6,66.6C37.1,68,21.5,77,21.5,77S5.9,68,4.4,66.6
								c-0.7-0.7-0.7-1.6-0.7-2h35.6C39.3,65,39.3,66,38.6,66.6z M39.4,61.2H3.6l-0.1-6.7h36C39.5,54.5,39.4,61.2,39.4,61.2z M39.6,50.9
								H3.4l-0.1-6.7h36.3V50.9z"/>
							</svg>
						</span>
						<span class="makespace-bee-wing wing-left">
							<svg xmlns="http://www.w3.org/2000/svg" width="33.1" height="45.1" viewBox="0 0 33.1 45.1" preserveAspectRatio="xMinYMin meet">
								<path class="makespace-orange wing-fill" d="M31,28.2c0,8.6-7.1,14.7-14.7,14.7c-2.7,0-5.3-0.8-7.9-2.3c-0.1,0-0.1-0.1-0.2-0.1c-8.4-5.4-8.5-17.7-0.1-23.2
									C16,12.1,24.8,6.5,31,3.2C31,3.2,31,28.2,31,28.2z"/>
								<path class="makespace-brown wing-outline"  d="M29.9,28.3c0,7.8-6.4,13.3-13.3,13.3c-2.4,0-4.8-0.7-7.1-2.1c-0.1,0-0.1-0.1-0.2-0.1c-7.6-4.9-7.7-16-0.1-20.9
									c7.1-4.7,15.1-9.8,20.7-12.8C29.9,5.7,29.9,28.3,29.9,28.3z M28.2,2.5c-4.8,2.6-12,7-21.2,13.1C2.6,18.6,0,23.7,0,29.1
									c0,5.4,2.8,10.4,7.4,13.3l0.2,0.1c2.7,1.7,5.8,2.6,9,2.6h0.1c8.8,0,16.5-6,16.5-16C33.1,29.2,33,0,33,0L28.2,2.5z"/>
							</svg>
						</span>
						<span class="makespace-bee-wing wing-right">
							<svg xmlns="http://www.w3.org/2000/svg" width="33.1" height="45.1" viewBox="0 0 33.1 45.1" preserveAspectRatio="xMinYMin meet">
								<path class="makespace-orange wing-fill"  d="M23.8,16.1c7.3,4.6,8.7,13.9,4.6,20.3c-1.4,2.3-3.5,4.1-6.2,5.4c-0.1,0.1-0.2,0-0.2,0.1
									c-9.1,4.2-19.5-2.2-19.7-12.3c-0.2-9.4-0.3-19.9,0.2-27C2.6,2.8,23.8,16.1,23.8,16.1z"/>
								<path class="makespace-brown wing-outline"  d="M3.2,5.7c5.6,3,13.6,8.1,20.7,12.8c7.6,4.9,7.5,16-0.1,20.9c-0.1,0-0.1,0.1-0.2,0.1c-2.3,1.4-4.7,2.1-7.1,2.1
									c-6.9,0-13.3-5.5-13.3-13.3C3.2,28.3,3.2,5.7,3.2,5.7z M0.1,0C0.1,0,0,29.2,0,29.2c0,10,7.6,16,16.5,16h0.1c3.2,0,6.3-0.9,9-2.6
									l0.2-0.1c4.6-2.9,7.4-7.9,7.4-13.3s-2.6-10.5-7.1-13.5C16.9,9.5,9.7,5.1,4.9,2.5L0.1,0z"/>
							</svg>
						</span>
					</span>
				</a>
				<a href="https://www.makespaceweb.com" title="Louisville Web Design" target="_blank" id="makespace-name" class="name-color-wave" <?php if( !is_front_page() ): ?>rel="nofollow" <?php endif ?>>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 260.7 47" width="260.7" height="47" preserveAspectRatio="xMinYMin meet">
						<path class="makespace-brown" d="M0 37L3.1.9h9.1c4.3 11.2 6.8 17.7 7.3 19.3.6 1.6 1 3.2 1.4 4.7.6-2.4 1.8-6 3.7-10.9L29.7.9h9.1L42.5 37h-8.2L33 23l-.7-10.6c-.7 2.2-3.7 10.2-8.9 24.2h-4.9c-4.9-13.3-7.8-21.3-8.5-24.2 0 2.5-.2 6.1-.5 10.6L8.2 37H0z"/>
						<path class="makespace-brown" d="M47.1 11.1c2.1-1.2 5.2-1.8 9.1-1.8 3.9 0 6.9.8 8.8 2.5 1.9 1.7 2.8 4.2 2.8 7.7V37h-7.7v-3.3c-1.1 2.3-3.4 3.5-6.7 3.5-2.6 0-4.7-.7-6.2-2.2-1.5-1.6-2.2-3.6-2.2-6s1-4.4 2.9-5.8c1.9-1.4 4.7-2.2 8.5-2.2h3.4v-1.9c0-2.3-1.5-3.4-4.5-3.4s-5.7.8-8.2 2.5v-7.1zm12.7 14.7h-2.5c-1.3 0-2.3.3-3.1.9-.7.6-1.1 1.3-1.1 2.2 0 1.8 1.1 2.7 3.2 2.7 1.1 0 2-.3 2.6-1 .6-.7.9-1.6.9-2.8v-2z"/>
						<path class="makespace-brown" d="M79.8 37H72V0h7.8v22l8.6-12.2h9l-9.4 13L97.5 37h-9.1l-8.7-13.3V37z"/>
						<path class="makespace-brown" d="M119.7 35.4c-1.9 1.2-4.9 1.8-9 1.8s-7.5-1.3-9.9-3.9c-2.5-2.6-3.7-6-3.7-10.2 0-4.2 1.2-7.5 3.6-10 2.4-2.4 5.4-3.7 9-3.7 3.6 0 6.4 1.1 8.5 3.2 2.1 2.1 3.2 5.3 3.2 9.5 0 .8-.1 2.2-.4 4.2h-16c.5 1.5 1.5 2.7 2.8 3.5 1.3.8 2.9 1.2 4.8 1.2 2.7 0 5.1-.8 7.2-2.3v6.7zm-5.6-14.5v-.6c-.3-3.2-1.6-4.8-4.2-4.8-1.3 0-2.4.5-3.3 1.4-1 .9-1.6 2.3-1.9 4h9.4z"/>
						<path class="makespace-brown" d="M135.4 15.6c-.9 0-1.6.2-2.1.6-.5.4-.7.8-.7 1.2 0 .4.1.8.3 1.1.2.3.5.6.9.8.4.3.8.5 1.2.7.4.2.9.4 1.5.7 2.4 1 4.2 2.2 5.4 3.5 1.1 1.3 1.7 2.8 1.7 4.6 0 2.7-1 4.7-2.9 6.2s-4.5 2.2-7.7 2.2-6-.6-8.2-1.7v-7c2.5 1.7 5.1 2.6 7.8 2.6 2 0 2.9-.6 2.9-1.9 0-.7-.4-1.2-1.2-1.7-.6-.3-1.3-.7-2.3-1.1-1-.4-1.6-.7-2-.9-3.6-1.7-5.4-4.3-5.4-7.7 0-2.6 1-4.7 3-6.2s4.5-2.2 7.4-2.2c2.9 0 5.3.4 7.1 1.1v6.7c-2-1-4.2-1.6-6.7-1.6z"/>
						<path class="makespace-brown" d="M154 9.8V14c1.9-3 4.6-4.5 8.1-4.5s6.4 1.2 8.5 3.7c2.1 2.5 3.2 5.8 3.2 9.9 0 4.1-1 7.5-3.1 10.2-2.1 2.6-4.9 4-8.6 4-3.7 0-6.4-1.4-8.2-4.3v14h-7.8V9.8h7.9zm0 13.3c0 2.3.6 4.1 1.7 5.5 1.1 1.4 2.6 2.1 4.3 2.1 1.7 0 3.2-.7 4.2-2 1.1-1.3 1.6-3.1 1.6-5.5s-.5-4.2-1.6-5.4c-1.1-1.3-2.5-1.9-4.1-1.9-1.7 0-3.1.6-4.3 1.9-1.2 1.3-1.8 3.1-1.8 5.3z"/>
						<path class="makespace-brown" d="M177.6 11.1c2.1-1.2 5.2-1.8 9.1-1.8 3.9 0 6.9.8 8.8 2.5 1.9 1.7 2.8 4.2 2.8 7.7V37h-7.7v-3.3c-1.1 2.3-3.4 3.5-6.7 3.5-2.6 0-4.7-.7-6.2-2.2-1.5-1.5-2.2-3.5-2.2-5.9 0-2.4 1-4.4 2.9-5.8 1.9-1.4 4.7-2.2 8.5-2.2h3.4v-1.9c0-2.3-1.5-3.4-4.5-3.4s-5.7.8-8.2 2.5v-7.2zm12.7 14.7h-2.5c-1.3 0-2.3.3-3.1.9-.7.6-1.1 1.3-1.1 2.2 0 1.8 1.1 2.7 3.2 2.7 1.1 0 2-.3 2.6-1 .6-.7.9-1.6.9-2.8v-2z"/>
						<path class="makespace-brown" d="M211.2 29c1.3 1.3 3 2 5.2 2s4.3-.6 6.4-1.9v6.5c-2.1 1.1-4.6 1.7-7.6 1.7-4.1 0-7.4-1.3-10.1-3.9-2.6-2.6-4-5.9-4-10 .1-4.1 1.4-7.4 4-10.1 2.6-2.7 6.1-4 10.6-4 2.7 0 5.1.5 7.1 1.6v6.7c-1.9-1.2-4-1.8-6.2-1.8s-4.1.7-5.4 2c-1.4 1.3-2.1 3.2-2.1 5.7.1 2.4.8 4.2 2.1 5.5z"/>
						<path class="makespace-brown" d="M246.9 35.4c-1.9 1.2-4.9 1.8-9 1.8s-7.5-1.3-9.9-3.9c-2.5-2.6-3.7-6-3.7-10.2 0-4.2 1.2-7.5 3.6-10 2.4-2.4 5.4-3.7 9-3.7s6.4 1.1 8.5 3.2c2.1 2.1 3.2 5.3 3.2 9.5 0 .8-.1 2.2-.4 4.2h-16c.5 1.5 1.5 2.7 2.8 3.5 1.3.8 2.9 1.2 4.8 1.2 2.7 0 5.1-.8 7.2-2.3v6.7zm-5.6-14.5v-.6c-.3-3.2-1.6-4.8-4.2-4.8-1.3 0-2.4.5-3.3 1.4-1 .9-1.6 2.3-1.9 4h9.4z"/>
						<path class="makespace-brown" d="M251.6 25.2V.8h8.4v24.4h-8.4z"/>
						<path class="makespace-brown" d="M260.5 29.9c-1.5-1.1-4-2.5-4.6-2.5h-.1-.1c-.6 0-3.1 1.4-4.6 2.5-.3.2-.3 4.9 0 5.2 1.5 1.1 4 2.5 4.6 2.5h.2c.6 0 3.1-1.4 4.6-2.5.3-.2.3-4.9 0-5.2z"/>
					</svg>
				</a>
			</div>
			</div>
			
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>