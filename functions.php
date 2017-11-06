<?php
// Theme support options
require_once(get_template_directory().'/assets/functions/theme-support.php'); 

// WP Head and other cleanup functions
require_once(get_template_directory().'/assets/functions/cleanup.php'); 

// Register scripts and stylesheets
require_once(get_template_directory().'/assets/functions/enqueue-scripts.php'); 

// Register custom menus and menu walkers
require_once(get_template_directory().'/assets/functions/menu.php'); 

// Register sidebars/widget areas
require_once(get_template_directory().'/assets/functions/sidebar.php'); 

// Makes WordPress comments suck less
require_once(get_template_directory().'/assets/functions/comments.php'); 

// Replace 'older/newer' post links with numbered navigation
require_once(get_template_directory().'/assets/functions/page-navi.php'); 

// Adds support for multiple languages
require_once(get_template_directory().'/assets/translation/translation.php'); 


// Remove 4.2 Emoji Support
// require_once(get_template_directory().'/assets/functions/disable-emoji.php'); 

// Adds site styles to the WordPress editor
//require_once(get_template_directory().'/assets/functions/editor-styles.php'); 

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/assets/functions/related-posts.php'); 

// Use this as a template for custom post types
// require_once(get_template_directory().'/assets/functions/custom-post-type.php');

// Customize the WordPress login menu
// require_once(get_template_directory().'/assets/functions/login.php'); 

// Customize the WordPress admin
// require_once(get_template_directory().'/assets/functions/admin.php'); 

//-------Begin required/included files-------

//get core theme functions
//Includes standard content, structure, and shortcode functions
//Intended for items that require little to no modifcation,
//as well as files that should always be included
require_once(get_stylesheet_directory() . '/assets/functions/core.php');

//-------End required/included files-------

//-------Begin external scripts-------

/**
add_action('wp_head', 'get_typekit');

function get_typekit() {
  echo '<script src="https://use.typekit.net/meu8bxo.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>';
}
*/

//-------End external scripts-------

//-------Begin Shortcodes------


//-------End Shortcodes-------

//-------Begin Global Variables------

$vc_custom_styles = ""; //Initialize variable for creating custom vc styles in the footer

//-------End Global Variables-------

//-------Begin Custom Options------

//Create custom theme settings that also import the core settings
class Joints_Custom_Options extends Joints_Core_Custom_Options {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    */
    
   public static function register ( $wp_customize ) {
       //Example for adding section
      /**$wp_customize->add_section( 'site_layout', 
         array(
            'title'       => __( 'Site Layout', 'joints' ), //Visible title of section
            'priority'    => 35, //Determines what order this appears in
            'capability'  => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Set standard page layout options', 'joints'), //Descriptive tooltip
         ) 
      );*/
      /** Example for adding custom setting and control
      $wp_customize->add_setting( 'column_width', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
             'default'    => '', 
             'type'       => 'option', 
             'capability' => 'edit_theme_options',
             'transport'  => 'postMessage', 
             'default' => '8',
         ) 
      );       
            
      $wp_customize->add_control(
         'column_width_control', //Set a unique ID for the control
         array(
             'label'      => __( 'Main Column Width', 'joints' ), 
             'settings'   => 'column_width', 
             'priority'   => 10, 
             'section'    => 'site_layout', 
             'type' => 'number',
             'input_attrs' => array(
                 'min' => '1',
                 'max' => '12',
             ),
             'description' => 'Set the column width of the main content on pages with sidebars.  It\'s recommended that the total column widths equal 12.',
         ) 
       );*/
       
       parent::register($wp_customize);

   }

}

add_action( 'customize_register' , array( 'Joints_Custom_Options' , 'register' ) );// Setup the Theme Customizer settings and controls...

add_action( 'wp_head' , array( 'Joints_Custom_Options' , 'header_output' ) ); // Output custom CSS to live site

add_action( 'customize_preview_init' , array( 'Joints_Custom_Options' , 'live_preview' ) ); // Enqueue live preview javascript in Theme Customizer admin screen


//-------End Custom Options------

//-------Begin Functions-------

//Code to create custom post type

/** add_action( 'init', 'create_slide_type' );
function create_slide_type() {
  register_post_type( 'intro_slide',
    array(
      'labels' => array(
        'name' => __( 'Intro Slides' ),
        'singular_name' => __( 'Intro Slide' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}*/

add_action('joints_intro_content', 'page_breadcrumbs');

function page_breadcrumbs() {
	global $post;
	
	//Terminate on the home page
	if(is_front_page()) {
		return;
	}
	echo '<div class="page-breadcrumbs">';

	$breadcrumbs = array(); //Initialize breadcrumbs
	$breadcrumbs[] = get_the_title();
    
	$ancestors = get_post_ancestors($post);
    
    //Get ancestors as links
	foreach($ancestors as $ancestor) {
		$breadcrumbs[] = '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
	}
    
	$breadcrumbs = array_reverse($breadcrumbs); //Reorder to use correct order
    
	echo implode(' \\ ', $breadcrumbs); //Concatenate the individual breadcrumbs into a single string
	echo '</div>';
}

//For modifying nav
//Sets primary nav to Foundation dropdown by default
add_action('joints_nav', 'call_nav_filter', 3);

function call_nav_filter() {
  if ( is_admin() ) {
        return;
    }
    add_filter('wp_nav_menu_items', 'custom_nav_filter', 1, 2);
}

function custom_nav_filter($items, $args) {
    
  if($args->menu->slug === 'primary-nav') {
      
	//Add sub-menu data for foundation dropdowns
    $args->items_wrap = '<ul id="%1$s" class="%2$s horizontal dropdown menu" data-dropdown-menu>%3$s</ul>';
    $items = preg_replace('/sub\-menu/', 'sub-menu menu vertical', $items);
  }
  return $items;
}

add_action('wp_footer', 'do_vc_custom_styles'); //Display styles from Visual Composer that can't be set inline

function do_vc_custom_styles() {
	global $vc_custom_styles;
	
	echo '<style>' .
		$vc_custom_styles . 
	'</style>';
}

//add_action('joints_entry_content', 'get_archive_intro', 8);

$archive_css = ''; //VC styles, because they won't load right in archives by default

//Pulls in intro content for archives.
//Allows content (for archives) to be pulled in from WP pages when they usually can't be
function get_archive_intro() {
	if(!is_archive()) {
		return;
	}
	
	global $archive_css;
	
	$slug = '';
	$product = get_queried_object();
	if(is_tax()) {
		$slug = $product->slug;
	}
	$args = array(
		'post_name'	=> $slug,
		'post_type'   => 'page',
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$pos = 0;
	$page = get_page_by_path($slug,OBJECT,'page');
	if(!empty($page)) {
		echo '<div class="nm-row">' . 
			do_shortcode($page->post_content) . 
		'</div>';
		
		//Extract styles from VC shortcode
		while(strpos($page->post_content, 'css=', $pos) !== false) {
			$pos_start = strpos($page->post_content, 'css=', $pos) + 5;
			$pos = strpos($page->post_content, '"', $pos_start);
			$archive_css .= substr($page->post_content, $pos_start, $pos - $pos_start) . ' ';
		}
	}
	remove_action('wp_footer', 'get_archive_css');
	add_action('wp_footer', 'get_archive_css');
}
function get_archive_css() {
	global $archive_css;
	echo '<style>' .
		$archive_css . 
	'</style>';
}

add_action('joints_before_nav', 'mobile_menu_hamburger');

function mobile_menu_hamburger() {
	echo '<div class="hamburger-menu-wrap" data-target=".top-sidebar">
		<span class="hamburger-menu"></span>
	</div>';
}
