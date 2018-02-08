<?php
/**
 * The template for displaying static front page
 *
 * @package WordPress
 * @subpackage webgl_makeup
 */

get_header(); ?>

<div class="modal fade booking-modal-sm" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="bookingModalLabel"></h4>
      </div>
      <div class="modal-body">
      	<div>
        <?= do_shortcode('[contact-form-7 id="82" title="booking"]'); ?>
        </div>
      </div>
    </div>
  </div>
</div>

	<!-- slider -->
	<div class="head-slider hidden-xs">
		<?php echo do_shortcode('[smartslider3 slider=2]'); ?>
	</div>

	<div class="visible-xs mobile-header-img">
		<h1><a href="/portfolio/">PORTFOLIO</a></h1>
	</div>

	<main  class="container content">
		<!-- about us -->
		<section>
			<h1 class="section-caption text-center">ABOUT</h1>
			<div>
				<?php while ( have_posts() ) : the_post(); ?>
					<?= the_content();//CFS()->get( 'about_us' ); ?>
				<?php endwhile;?>
			</div>
				<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4" style="margin-top: 10px">
					<a class="btn btn-default btn-block" href="/about/"><span>READ MORE</span></a>
					<!-- data-toggle="modal" data-target=".booking-modal-sm"  -->
				</div>
		</section>

		<!-- services -->
		<section class="services-section">
			<h1 class="section-caption text-center">SERVICES</h1>

			<div>
				<?= CFS()->get( 'services' ); ?>
			</div>

				<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4" style="margin-top: 10px">
					<a class="btn btn-default btn-block" href="/services/"><span>READ MORE</span></a>
					<!-- data-toggle="modal" data-target=".booking-modal-sm"  -->
				</div>
		</section>

		<!-- makeup courses -->
		<section>
			<h1 class="section-caption text-center">MAKEUP COURSES</h1>
			<div class="col-md-3 col-sm-3 courses-img">
				<div>
					<a href="/courses/#beginners"><img src="<?php echo get_template_directory_uri(); ?>/images/courses_beginners.jpg" alt="professional makeup courses for beginners">
						<span class="text-white">PROFESSIONAL MAKEUP COURSE (BEGINNERS)</span>
					</a>
				</div>
			</div>

			<div class="col-md-3 col-sm-3 courses-img">
				<div>
					<a href="/courses/#advanced"><img src="<?php echo get_template_directory_uri(); ?>/images/courses_advanced.jpg"  alt="professional makeup courses advanced">
						<span class="text-white">PROFESSIONAL MAKEUP COURSE (ADVANCED)</span>
					</a>
				</div>
			</div>

			<div class="col-md-3 col-sm-3 courses-img">
				<div>
					<a href="/courses/#individual"><img src="<?php echo get_template_directory_uri(); ?>/images/courses_private.jpg"  alt="professional makeup courses of airbrush">
						<span class="text-white">INDIVIDUAL PRIVATE CLASS</span>
					</a>
				</div>
			</div>

			<div class="col-md-3 col-sm-3 courses-img">
				<div>
					<a href="/courses/#airbrush"><img src="<?php echo get_template_directory_uri(); ?>/images/courses_airbrush.jpg"  alt="professional makeup courses of airbrush">
						<span class="text-white">AIRBRUSH</span>
					</a>
				</div>
			</div>

			<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4" style="margin-top: 30px">
				<button class="btn btn-default btn-block" data-title="BOOK YOUR CLASS" data-toggle="modal" data-target=".booking-modal-sm" data-val="Order a course"><span>BOOK YOUR CLASS</span></button>
			</div>

		</section>
	</main>

	<div class="referer">
		<?= CFS()->get( 'last_paragraph' ); ?>
	</div>

	<div class="container callback-form">
		<p class="text-center">If you have any questions, please, fill out the form !</p>
		<?= do_shortcode('[contact-form-7 id="43" title="callback"]'); ?>
	</div>

<?php get_footer(); ?>