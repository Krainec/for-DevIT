<?php
/**
 * webgl_makeup functions and definitions
 *
 * @package WordPress
 * @subpackage webgl_makeup
 */

/**
 * regiatering scripts and styles
 */
add_action('wp_enqueue_scripts', 'attach_scripts');

/**
 * handler for wp_enqueue_scripts
 */
function attach_scripts(){
	// css
	wp_enqueue_style('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
	wp_enqueue_style('style', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('style-woo', get_template_directory_uri() . '/css/style-woo.css');

	// javascript
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
	wp_enqueue_script('mobileNav', get_template_directory_uri() . '/js/menu-mobile.js');
}

/**
 * registering menu positions
 */
add_action('after_setup_theme', function(){
	register_nav_menus([
		'top'    => 'Верхнее меню',
		'bottom' => 'Нижнее меню',
	]);

	add_theme_support('title-tag');
});


/**
 * comments rendering functions
 * for comments.php
 */
function commentBuilder($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	echo '<li class="comment-item">';
	echo '<p class="comment-author">'.get_comment_author($comment).'</p>';
	echo comment_text();
}

function commentCloser(){
	echo '</li>';
}

add_theme_support( 'post-thumbnails' ); 

/**
 * creating custom post types for: 
 * - blog
 * - vlog
 * - courses
 */
add_action('init', 'register_post_types');

/**
 * handler for init hook 
 */
function register_post_types(){

	// taxonomy for video blog items
	$labels = array(
		'name'              => 'Vlog categories',
		'singular_name'     => 'Vlog category',
		'search_items'      => 'Search video categories',
		'all_items'         => 'All video categories',
		'edit_item'         => 'Edit video categories',
		'update_item'       => 'Update video category',
		'add_new_item'      => 'Add video category to vlog',
		'new_item_name'     => 'New video category name',
		'menu_name'         => 'VLOG categories',
	); 
	
	$args = array(
		'labels'                => $labels,
		'hierarchical'          => false,
		'show_ui'				=> false,
		'rewrite'               => ['slug' => 'vlog'],
		'query_var'             => true,
		'publicly_queryable'    => true,
	);

	register_taxonomy('vlog', ['video'], $args );

	// video blog post type
	register_post_type('video', [
		'labels' => [
			'name'               => 'VLOG',
			'singular_name'      => 'VLOG',
			'add_new'            => 'Add video',
			'add_new_item'       => 'Add new video',
			'edit_item'          => 'Edit video',
			'new_item'           => 'New video',
			'view_item'          => 'View video',
			'search_items'       => 'Search video',
			'not_found'          => 'Video not found',
			'not_found_in_trash' => 'Video not found in trash',
			'menu_name'          => 'VLOG',
		],
		'description'         => 'Videos from your VLOG',
		'public'              => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-format-video', 
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'supports'            => ['title', 'editor'],
		'taxonomies'          => ['vlog'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );

	/** ----------------------------------------------- **/

	// taxonomy for blog items
	$labels = array(
		'name'              => 'Blog categories',
		'singular_name'     => 'Blog category',
		'search_items'      => 'Search blog categories',
		'all_items'         => 'All article categories',
		'edit_item'         => 'Edit article categories',
		'update_item'       => 'Update article',
		'add_new_item'      => 'Add category to blog',
		'new_item_name'     => 'New category name',
		'menu_name'         => 'BLOG categories',
	); 
	
	$args = array(
		'labels'                => $labels,
		'description'           => 'Your blog categories place in this area',
		'show_ui'				=> false,
		'hierarchical'          => false,
		'rewrite'               => ['slug' => 'blog'],
		'query_var'             => true,
		'publicly_queryable'    => true,
	);

	register_taxonomy('blog', ['blog'], $args );

	// blog post type
	register_post_type('blog', [
		'labels' => [
			'name'               => 'BLOG',
			'singular_name'      => 'BLOG',
			'add_new'            => 'Add article',
			'add_new_item'       => 'Add new article',
			'edit_item'          => 'Edit article',
			'new_item'           => 'New article',
			'view_item'          => 'View article',
			'search_items'       => 'Search article',
			'not_found'          => 'Article not found',
			'not_found_in_trash' => 'Article not found in trash',
			'menu_name'          => 'BLOG',
		],
		'description'         => 'Articles from your BLOG',
		'public'              => true,
		'menu_position'       => 3,
		'menu_icon'           => 'dashicons-feedback', 
		'capability_type'   => 'post',
		'hierarchical'        => true,
		'supports'            => ['title','editor','thumbnail'],
		'taxonomies'          => ['blog'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );

	/** ----------------------------------------------- **/

	// taxonomy for courses
	$labels = array(
		'name'              => 'Courses categories',
		'singular_name'     => 'Courses category',
		'search_items'      => 'Search courses categories',
		'all_items'         => 'All courses categories',
		'edit_item'         => 'Edit course categories',
		'update_item'       => 'Update course category',
		'add_new_item'      => 'Add course category',
		'new_item_name'     => 'New course category name',
		'menu_name'         => 'Courses categories',
	); 
	
	$args = array(
		'labels'                => $labels,
		'description'           => 'Your awesome courses categories place in this area',
		'public'                => false,
		'show_in_nav_menus'     => false,
		'hierarchical'          => false,
		'rewrite'               => ['slug' => 'courses'],
		'query_var'             => true,
		'publicly_queryable'    => true,
	);

	register_taxonomy('courses', array('courses'), $args );

	// courses post type
	register_post_type('courses', [
		'labels' => [
			'name'               => 'Courses',
			'singular_name'      => 'Course',
			'add_new'            => 'Add course',
			'add_new_item'       => 'Add new course',
			'edit_item'          => 'Edit course',
			'new_item'           => 'New course',
			'view_item'          => 'View course',
			'search_items'       => 'Search course',
			'not_found'          => 'Course not found',
			'not_found_in_trash' => 'Course not found in trash',
			'menu_name'          => 'Courses',
		],
		'description'         => 'List of my awesome courses',
		'public'              => true,
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-welcome-learn-more', 
		'capability_type'   => 'post',
		'hierarchical'        => false,
		'supports'            => ['title', 'editor', 'thumbnail'],
		'taxonomies'          => ['courses'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );

	/** ----------------------------------------------- **/

	// taxonomy for portfolio
	$labels = array(
		'name'              => 'Portfolio categories',
		'singular_name'     => 'Portfolio category',
		'search_items'      => 'Search portfolio categories',
		'all_items'         => 'All portfolio categories',
		'edit_item'         => 'Edit portfolio categories',
		'update_item'       => 'Update portfolio category',
		'add_new_item'      => 'Add portfolio category',
		'new_item_name'     => 'New portfolio category name',
		'menu_name'         => 'Portfolio categories',
	); 
	
	$args = array(
		'labels'                => $labels,
		'description'           => 'Your awesome portfolio categories place in this area',
		'public'                => false,
		'show_in_nav_menus'     => false,
		'hierarchical'          => false,
		'rewrite'               => ['slug' => 'folio'],
		'query_var'             => true,
		'publicly_queryable'    => true,
	);

	register_taxonomy('folio', array('portfolio'), $args );

	// portfolio post type
	register_post_type('portfolio', [
		'labels' => [
			'name'               => 'Portfolio',
			'singular_name'      => 'Portfolio',
			'add_new'            => 'Add portfolio item',
			'add_new_item'       => 'Add new portfolio item',
			'edit_item'          => 'Edit portfolio item',
			'new_item'           => 'New portfolio item',
			'view_item'          => 'View portfolio item',
			'search_items'       => 'Search portfolio items',
			'not_found'          => 'Portfolio item not found',
			'not_found_in_trash' => 'Portfolio item not found in trash',
			'menu_name'          => 'Portfolio',
		],
		'description'         => 'My awesome portfolio',
		'public'              => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-schedule', 
		'capability_type'   => 'post',
		'hierarchical'        => false,
		'supports'            => ['title', 'editor'],
		'taxonomies'          => ['folio'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );

	/** ----------------------------------------------- **/

	// taxonomy for services
	$labels = array(
		'name'              => 'Services categories',
		'singular_name'     => 'Service category',
		'search_items'      => 'Search service categories',
		'all_items'         => 'All service categories',
		'edit_item'         => 'Edit service categories',
		'update_item'       => 'Update service category',
		'add_new_item'      => 'Add service category',
		'new_item_name'     => 'New service category name',
		'menu_name'         => 'Services categories',
	); 
	
	$args = array(
		'labels'                => $labels,
		'description'           => 'Your awesome services categories place in this area',
		'public'                => false,
		'show_in_nav_menus'     => false,
		'hierarchical'          => false,
		'rewrite'               => ['slug' => 'serv'],
		'query_var'             => true,
		'publicly_queryable'    => true,
	);

	register_taxonomy('serv', ['services'], $args );

	// service post type
	register_post_type('services', [
		'labels' => [
			'name'               => 'Services',
			'singular_name'      => 'Service',
			'add_new'            => 'Add service',
			'add_new_item'       => 'Add new service',
			'edit_item'          => 'Edit service',
			'new_item'           => 'New service',
			'view_item'          => 'View service',
			'search_items'       => 'Search service',
			'not_found'          => 'Service not found',
			'not_found_in_trash' => 'Service not found in trash',
			'menu_name'          => 'Services',
		],
		'description'         => 'My awesome services',
		'public'              => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-store', 
		'capability_type'   => 'post',
		'hierarchical'        => false,
		'supports'            => ['title', 'thumbnail', 'editor'],
		'taxonomies'          => ['serv'],
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

function breadcrumbs($separator = ' » ', $home = 'Главная') {

	$path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
	$base_url = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
	$breadcrumbs = array("<a href=\"$base_url\">$home</a>");

	$last = end( array_keys($path) );

	foreach( $path as $x => $crumb ){
		$title = ucwords(str_replace(array('.php', '_'), Array('', ' '), $crumb));
		if( $x != $last ){
			$breadcrumbs[] = '<a href="'.$base_url.$crumb.'">'.$title.'</a>';
		}
		else {
			$breadcrumbs[] = $title;
		}
	}

	return implode( $separator, $breadcrumbs );
}

/**
 * adding options page to admin menu
 * allows to edit contacts
 */
function theme_options(){
	add_theme_page('Edit contacts', 'Contacts', 'manage_options', 'admin-contacts', 'theme_contacts_page');
}
add_action('admin_menu', 'theme_options');

function theme_contacts_page(){
	?>

	<h1>Theme contacts in footer</h1>

	<form method="post" action="options.php">
		<?
			wp_nonce_field('update-options'); 
			settings_fields('theme_contacts');
			//do_settings_sections('admin-contacts');
		?>
		<div style="width: 300px">
			<label>
				First phone number <br/>
				<input type="text" name="phone0" value="<?= get_option('phone0'); ?>"/>
			</label>
		</div>

		<div style="width: 300px">
			<label>
				Second phone number <br/>
				<input type="text" name="phone1" value="<?= get_option('phone1'); ?>"/>
			</label>
		</div>

		<div style="width: 300px">
			<label>
				Third phone number <br/>
				<input type="text" name="phone2" value="<?= get_option('phone2'); ?>"/>
			</label>
		</div>

		<div style="width: 300px">
			<label>
				Email<br/>
				<input type="text" name="email" value="<?= get_option('email'); ?>"/>
			</label>
		</div>

		<div style="width: 300px">
			<label>
				Address <br/>
				<input type="text" name="address" value="<?= get_option('address'); ?>"/>
			</label>
		</div>
		<p class="submit">  
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
        </p>
	</form>


	<?
}

add_action('admin_init', 'register_theme_settings');

function register_theme_settings(){
	register_setting('theme_contacts', 'phone0');
	register_setting('theme_contacts', 'phone1');
	register_setting('theme_contacts', 'phone2');

	register_setting('theme_contacts', 'email');

	register_setting('theme_contacts', 'address');
}

/**
 * Brand register
 */
function add_product_tax_brand() {
    register_taxonomy(
        'brand',
        'product',
        array(
            'hierarchical' => true,
            'rewrite'=>'true',
            'labels' => array(
                'name' 		=> 'Brands',
                'all_items'	=> 'All brands'
            ),
            'query_var' => false
        )
    );
}
add_action( 'init', 'add_product_tax_brand');

add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_delimiter' );
function jk_change_breadcrumb_delimiter( $defaults ) {
// Изменяем разделитель хлебных крошек с '/' на '>'
    $defaults['delimiter'] = ' &gt; ';
    return $defaults;
}