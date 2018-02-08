<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage webgl_makeup
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header>
	<div class="container">
		<a href="<?= home_url(); ?>">
		<img src="<?php echo get_template_directory_uri() . '/images/logo.png'; ?>" class="center-block img-responsive visible-md visible-lg"  alt="Larisa Larina - makeup expert. New York">
		<img src="<?php echo get_template_directory_uri() . '/images/logo.png'; ?>" class="mobile-logo img-responsive visible-xs visible-sm" alt="Larisa Larina - makeup expert. New York">
		</a>
		<div class="mobile visible-xs visible-sm">
            <a class='top_basket' href="/?p=1304"><img src='<?php bloginfo("template_url") ?>/images/icon_basket.png' alt="basket"></a>
			<div class="mobile-nav-toggle">
				<div class="mobile-menu">
					<?php 
						wp_nav_menu([
						   	'theme_location'=>'top',
						    'depth' => 1,
						]);
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="container topmenu">
        <a class='top_basket hidden-xs hidden-sm' href="/?p=1304"><img src='<?php bloginfo("template_url") ?>/images/icon_basket.png' alt="basket"></a>
<!--        <a class='top_basket' href="/?p=1304"><img src='--><?php //bloginfo("template_url") ?><!--/images/FBlogo.png' alt="basket"></a>-->
		<?php
			wp_nav_menu([
				'menu_class'=>'nav navbar-nav visible-md visible-lg',
			    'theme_location'=>'top',
			    'container_id' => 'navbar-collapse',
			    'depth' => 1,
			]);
		?>
	</div>

<?php
if( is_page( array( 'contacts', 'reviews', 'about' ) )||
    is_post_type_archive(array('video', 'blog', 'courses', 'portfolio', 'services', 'product')))
{
    echo '<div class="container breadcrumbs hidden-xs">'.breadcrumbs(' > ', 'Home').'</div>';
}
?>
	<?//= !is_front_page() ? '<div class="container breadcrumbs hidden-xs">'.breadcrumbs(' > ', 'Home').'</div>' : ''; ?>
</header>
