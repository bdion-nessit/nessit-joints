<?php

//-------Begin required/included files-------

//add sidebars to theme
require_once(get_stylesheet_directory() . '/widget_areas.php');

//add custom button widget to theme
require_once(get_stylesheet_directory() . '/assets/functions/button_widget.php');

//add custom VC widgets and functions
require_once(get_stylesheet_directory() . '/vc_custom.php');

//add custom pagination functions
require_once(get_stylesheet_directory() . '/assets/functions/pagination.php');

//Add custom sorting functionality
require_once(get_stylesheet_directory() . '/assets/functions/custom_post_query.php');

//Add custom functionality for updating posts
require_once(get_stylesheet_directory() . '/assets/functions/update_post.php');

//-------End required/included files-------

//-------Begin external scripts-------

//add general custom scripts 
add_action('wp_head', 'get_theme_scripts');
add_action('wp_head', 'get_slider_scripts');
add_action('wp_footer', 'get_modal_scripts');

function get_theme_scripts() {
  echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/theme_scripts.js"></script>';
}

//add single item slider scripts 
function get_slider_scripts() {
  echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/slider.js"></script>';
}

//add modal scripts
function get_modal_scripts() {
	echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/modal.js"></script>';
}

//add multi item slider scripts
function get_multi_slider_scripts() {
	echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/multi_slider.js"></script>';
}

//Get the script for pagination, and allow for its ajax connection
add_action('wp_enqueue_scripts', 'enqueue_blog_load_more');
add_action( 'wp_ajax_blog_load_more', 'do_blog_load_more' );
add_action( 'wp_ajax_nopriv_blog_load_more', 'do_blog_load_more' );

function enqueue_blog_load_more() {
  wp_enqueue_script( 'blog_load_more_ajax', get_stylesheet_directory_uri() . '/assets/js/pagination_ajax.js', array('jquery'), '1.0', true );

  wp_localize_script( 'blog_load_more', 'ajax_admin_url', array(
    'ajax_url' => admin_url( 'admin-ajax.php' )
  ));
}

//Get the script for post sorting, and allow for its ajax connection
add_action('wp_enqueue_scripts', 'enqueue_custom_post_query');
add_action( 'wp_ajax_custom_post_query', 'do_custom_post_query' );
add_action( 'wp_ajax_nopriv_custom_post_query', 'do_custom_post_query' );

function enqueue_custom_post_query() {
  wp_enqueue_script( 'custom_post_query_ajax', get_stylesheet_directory_uri() . '/assets/js/custom_post_query_ajax.js', array('jquery'), '1.0', true );

  wp_localize_script( 'custom_post_query_ajax', 'ajax_admin_url', array(
    'ajax_url' => admin_url( 'admin-ajax.php' )
  ));
}

//Get the script for updating posts, and allow for its ajax connection
add_action('wp_enqueue_scripts', 'enqueue_update_post');
add_action( 'wp_ajax_update_post', 'update_post' );
add_action( 'wp_ajax_nopriv_update_post', 'update_post' );

function enqueue_update_post() {
  wp_enqueue_script( 'update_post_ajax', get_stylesheet_directory_uri() . '/assets/js/update_post_ajax.js', array('jquery'), '1.0', true );

  wp_localize_script( 'update_post_ajax', 'ajax_admin_url', array(
    'ajax_url' => admin_url( 'admin-ajax.php' )
  ));
}

//Get preloader to display while page content loads
add_action('wp_head', 'preloader');

function preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader.css" rel="stylesheet" type="text/css">
  <?php
}

//Load styles to hide preloader
//Tucked at the bottom of the page, so that almost everything will naturally load before it
add_action('wp_footer', 'end_preloader', 50);

function end_preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader-end.css" rel="stylesheet" type="text/css">
  <?php
}

//-------End external scripts-------

//Tells sidebar widgets to run shortcodes on content
add_filter('widget_text','do_shortcode');

//Ensure that VC styles are always loaded
add_action('joints_entry', 'blog_filler');

function blog_filler() {
	do_shortcode('[vc_row el_class="blog-filler"][vc_column][/vc_column][/vc_row]');
}

//-------Begin Global Variables------

//Column width of main content on pages with sidebar
$column_width = (!empty(get_option('column_width')) ? get_option('column_width') : 8);

//Column width of primary sidebar on pages it's used.  
$sidebar_width = (!empty(get_option('sidebar_primary_width')) ? get_option('sidebar_primary_width') : 4);

//-------End Global Variables------

//-------Begin Custom Options------

//Add core custom options to theme customizer
//To be pulled in by Joints_Custom_Options in functions.php
class Joints_Core_Custom_Options {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    */
    
    //Create Site Layout Section
   public static function register ( $wp_customize ) {
      $wp_customize->add_section( 'site_layout', 
         array(
            'title'       => __( 'Site Layout', 'joints' ), //Visible title of section
            'priority'    => 35, //Determines what order this appears in
            'capability'  => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Set standard page layout options', 'joints'), //Descriptive tooltip
         ) 
      );
      
       //Create setting and control for main column width on pages with sidebars
      $wp_customize->add_setting( 'column_width', 
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
       );
       
       //Create setting and control for the primary sidebar
       $wp_customize->add_setting( 'sidebar_primary_width', 
         array(
             'default'    => '4', 
             'type'       => 'option', 
             'capability' => 'edit_theme_options',
             'transport'  => 'postMessage', 
         ) 
      );       
            
      $wp_customize->add_control(
         'sidebar_primary_width_control', //Set a unique ID for the control
         array(
             'label'      => __( 'Primary Sidebar Width', 'joints' ), 
             'settings'   => 'sidebar_primary_width', 
             'priority'   => 11, 
             'section'    => 'site_layout', 
             'type' => 'number',
             'default' => '4',
             'input_attrs' => array(
                 'min' => '1',
                 'max' => '12',
             ),
             'description' => 'Set the column width of the primary sidebar.  It\'s recommended that the total column widths equal 12.',
         ) 
       );
     

      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
       /**
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
      $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
      $wp_customize->get_setting( 'background_color' )->transport = 'postMessage';*/
   }

   /**
    * This will output the custom WordPress settings to the live theme's WP head.
    */
   public static function header_output() {
      ?>
      <!--Customizer CSS--> 
      <style type="text/css">
           <?php self::generate_css('#site-title a', 'color', 'header_textcolor', '#'); ?> 
           <?php self::generate_css('body', 'background-color', 'background_color', '#'); ?> 
           <?php self::generate_css('a', 'color', 'link_textcolor'); ?>
      </style> 
      <!--/Customizer CSS-->
      <?php
   }
   
   /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings 
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    */
   public static function live_preview() {
      wp_enqueue_script( 
           'mytheme-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     */
    public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

//-------End Custom Options------

//-------General Functions-------

//General function to close divs
function close_element() {
	?>
	</div>
	<?php
}

//get featured image at pre-determined size
function get_featured() {
	the_post_thumbnail('full');
}

//Opens a visual composer row with a column inside of the user's choice of size
function open_vc_row_wrapper($i = 12) {
    return '<div class="vc_row">
            <div class="vc_col-sm-12">
                <div class="vc_column-inner">';
}

//Opens a visual composer row and column
function close_vc_row_wrapper() {
    return '</div></div></div>';
}

//-------Begin Shortcodes------

add_shortcode('get_search_box', 'get_search_form'); //Makes the base search form widget accessible by shortcode
add_shortcode('inline_custom_button', 'get_custom_button'); //Shortcode to use the custom button widget on page
add_shortcode('joints_site_map', 'get_site_map'); //Site map shortcode for the sake of ease
add_shortcode('content_slider_controls', 'get_slider_content_controls'); //Get single item slider controls
add_shortcode('multi_slider', 'get_multi_slider'); //Get multi slider

//Shortcode to use the custom button widget on pages
function get_custom_button($atts) {
  ob_start();
  the_widget('Custom_Button', $atts);
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}

//Site map shortcode for the sake of ease
function get_site_map() {
  $primary = wp_get_nav_menu_items('primary-nav');
  $output = '<div class="vc_column_container vc_col-sm-3">';
  $i = 1;
  foreach($primary as $ind => $item) {
    if($item->menu_item_parent == 0 ) {
      if($ind != 0){
        $output .= '</div><div class="vc_column_container vc_col-sm-3">';
      } 
      $output .= '<strong>';
    }
    $output .= '<a href="' . $item->url . '">' . $item->title . '</a><br />';
    if($item->menu_item_parent == 0 ) {
      $output .= '</strong>';
    }
  }
  $output .= '</div>';
  return $output;
}

//Get single item slider controls
function get_slider_content_controls() {
  return do_shortcode('[inline_custom_button class="content-slide-prev content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]') . 
  do_shortcode('[inline_custom_button class="content-slide-next content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]');
}

//Get multi slider
function get_multi_slider($atts) {
	$i = 1;
	$output = '<div class="multi-slider-wrap">
		<div class="multi-slider"><!--';
	if(!empty($atts['items'])) {
		foreach($atts['items'] as $item) {
			$styles = array();
			$data = array();
			
			$bg = wp_get_attachment_url($item['img']);
			if(!empty($bg)) {
				$styles[] = 'background-image: url(\'' . $bg . '\')';
			}
			
			if(!empty($item['meta']['title'])) {
				$data[] = 'data-title="' . $item['meta']['title'] . '"';
			}
			if(!empty($item['meta']['caption'])) {
				$data[] = 'data-caption="' . $item['meta']['caption'] . '"';
			}
			
			$output .= '--><div class="multi-slide multi-slide-' . $i . ' vc_col-sm-3" style="' . implode('; ', $styles) . '" ' . implode(' ', $data) .'>
				<div class="multi-slide-inner multi-slide-' . $i . '-inner"> 
			  </div>
			</div><!--';
			$i++;
		}	
	}
	else {
		$type = (!empty($atts['type']) ? $atts['type'] : 'post');
		$args = array(
    	'post_type' => $type,
		'post_status' => 'publish',
		'posts_per_page' => -1,
	  );
	$query1 = new WP_Query($args);
	if($query1->have_posts()) {
	  while($query1->have_posts()) {
		$query1->the_post();
		$output .= '--><div class="multi-slide multi-slide-' . $i . ' vc_col-sm-3">
			<div class="multi-slide-inner multi-slide-' . $i . '-inner">' . 
			get_the_content() . 
		  '</div>
		</div><!--';
		$i++;
	  }
  }
  wp_reset_postdata(); 
	}
  $output .= '--></div>
  <div class="multi-controls">
    <img src="' . get_stylesheet_directory_uri() . '/images/multi_prev.png" alt="Previous Review" class="multi-control multi-prev" />
    <img src="' . get_stylesheet_directory_uri() . '/images/multi_next.png" alt="Next Review" class="multi-control multi-next" />
  </div>';
  return $output . '</div>';
}

//-------End Shortcodes-------

//-------Begin Header Functionality-------

add_action('wp_head', 'check_permission', 1);

function check_permission() {
	$permission = get_post_meta(get_the_ID(), 'restriction', true);
	$user = wp_get_current_user();
	
	switch($permission) {
		case 'user':
			if($user->ID === 0) {
				header('location: /login/');
			}
			break;
		case 'admin': 
			if($user->ID === 0) {
				header('location: /login/');
			}
			elseif(!in_array('administrator', $user->roles)) {
				header('location: /');
			}
			break;
	}
}

add_action('joints_before_nav', 'get_site_logo');

function get_site_logo() {
	echo '<a href="' . get_home_url() . '" class="site-logo"><img src="' . get_site_icon_url() . '" alt="' . get_bloginfo('name') . '" /></a>';
}

	//-------Begin Intro Section-------

add_filter('intro_class', 'initialize_classes_intro', 1);
add_filter('intro_class', 'do_classes_intro', 99);

function initialize_classes_intro() {
	return array('intro');
}
function do_classes_intro($classes) {
	echo 'class="' . implode(' ', $classes) . '"';
}

//open intro block with any custom-set classes
add_action('joints_intro', 'open_intro', 2);

//open intro content block
add_action('joints_intro', 'do_intro_content');

//do intro content inner hook
add_action('joints_intro_content', 'open_intro_content', 2);

//get intro content
add_action('joints_intro_content', 'get_intro_content');

//close intro content
add_action('joints_intro_content', 'close_intro_content', 50);
add_action('joints_intro_content', 'close_element', 50);
add_action('joints_intro', 'close_element', 50);

//open intro block with any custom-set classes
function open_intro() {
	?>
	<div <?php apply_filters('intro_class', 'intro') ?>>
	<?php
}

//open intro content block
function open_intro_content() {
	?>
	<div class="intro-content">
		<div class="vc_row">
			<div class="vc_column_container vc_col-sm-6">
				<div class="vc_column-inner">
	<?php
}

//Intro content inner hook
function do_intro_content() {
	do_action('joints_intro_content');
}

//get intro content
function get_intro_content() {
    
    //get id of blog page
	$page_for_posts_id = get_option('page_for_posts');
    
    //use the id of the blog page (and thus its content), for itself and for archives
	$page = (is_home() || is_archive() ? $page_for_posts_id : null);
    
    //Get intro content from custom field
	echo //(is_front_page() ? '' : get_the_title()) .
		(function_exists('get_field') ? get_field('intro_content', $page) : '');
}

//close intro content
function close_intro_content() {
	?>
	</div>
		</div>
			</div>
	<?php
}

	//-------End Intro Section-------

add_action('joints_nav', 'joints_do_topbar');

function joints_do_topbar() {
	echo '<div class="top-sidebar">';
	dynamic_sidebar('top_sidebar'); 
	echo '</div>';
}

//-------End Header Functionality-------

//-------Begin Loop-------

add_action('joints_entry', 'joints_loop');

function joints_loop() {
  if (have_posts()) { 
  		while (have_posts()) { 
  			the_post();
            
            //load appropriate loop template for post type
    		if(get_post_type() === 'page') {
        		get_template_part( 'parts/loop', 'page' );
      		}
      		elseif(is_single()) {
      			get_template_part( 'parts/loop', 'single' );
      		}
      		else {
      			get_template_part( 'parts/loop', 'archive' );
      		}
		}
      
      //load paginated navigation for archives
		if(is_archive() || is_home()) {
			joints_page_navi();
		}
	}
    
    //if no content found
	else {
		get_template_part( 'parts/content', 'missing' );
	} 
}

//-------End Loop-------

//-------Begin Entry Header

//initialize classes for entry header
//add_filter('entry_header_class', 'initialize_entry_header_class', 1);
add_filter('entry_header_class', 'do_entry_header_class', 99);

function initialize_entry_header_class() {
	return array('entry-header');
}
function do_entry_header_class($classes) {
	echo 'class="' . implode(' ', $classes) . '"';
}

//open entry-header with custom-set classes
add_action('joints_entry_header', 'open_entry_header', 1);

//close entry header
add_action('joints_entry_header', 'close_entry_header', 99);

//open entry header with custom-set classes
function open_entry_header() {
	?>
	<header <?php apply_filters('entry_header_class', array('entry-header')); ?>>
	<?php
}

//close entry header
function close_entry_header() {
	?>
	</header>
	<?php
}

//get title specifically for archive title
function get_archive_title() {
	?>
	<h2>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<?php  
}

//get byline template
function get_byline() {
	get_template_part( 'parts/content', 'byline' );
}

//-------End Entry Header-------

//-------Begin Entry Content-------

//open entry content
add_action('joints_entry_content', 'open_entry_content', 1);

//close entry content
add_action('joints_entry_content', 'close_entry_content', 99);

//get entry content
add_action('joints_entry_content', 'the_entry_content', 9);

//open entry content
function open_entry_content() {
	?>
	<section class="entry-content" itemprop="articleBody">
	<?php
}

//close entry content
function close_entry_content() {
	?>
	</section> <!-- end article section -->
	<?php
}

//get entry content
function the_entry_content() {
    
    //get content
    //if blog page or archive, get them with "Read more" button
	if(is_archive() || is_home()) {
		the_content('<button class="tiny">' . __( 'Read more...', 'jointswp' ) . '</button>');
	}
	else {
		the_content();
	}
}

//-------End Entry Content-------

//-------Begin Sidebar

//get standard sidebar in primary sidebar position
add_action('joints_primary_sidebar', 'get_sidebar');

//get primary sidebar inside standard sidebar
add_action('joints_sidebar_inner', 'get_primary_sidebar');

//get primary sidebar
function get_primary_sidebar() {
	if ( is_active_sidebar( 'sidebar1' ) ) {
		dynamic_sidebar( 'sidebar1' );
	}
}

//-------End Sidebar
