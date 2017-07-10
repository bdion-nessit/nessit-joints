<?php

add_filter('widget_text','do_shortcode');

//Ensure that VC styles are always loaded
add_action('joints_entry', 'blog_fillter');

function blog_filler() {
	do_shortcode('[vc_row el_class="blog-filler"][vc_column][/vc_column][/vc_row]');
}

//-------Begin external scripts-------

add_action('wp_head', 'preloader');

function preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader.css" rel="stylesheet" type="text/css">
  <?php
}

add_action('wp_footer', 'end_preloader', 50);

function end_preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader-end.css" rel="stylesheet" type="text/css">
  <?php
}

//-------End external scripts-------

//-------General Functions-------
function close_element() {
	?>
	</div>
	<?php
}
function get_featured() {
	the_post_thumbnail('full');
}

//-------Begin Shortcodes------

add_shortcode('get_search_box', 'get_search_form');
add_shortcode('inline_custom_button', 'get_custom_button');
add_shortcode('joints_site_map', 'get_site_map');
add_shortcode('content_slider_controls', 'get_slider_content_controls');

function get_custom_button($atts) {
  ob_start();
  the_widget('Custom_Button', $atts);
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}
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
function get_slider_content_controls() {
  return do_shortcode('[inline_custom_button class="content-slide-prev content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]') . 
  do_shortcode('[inline_custom_button class="content-slide-next content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]');
}

//-------End Shortcodes-------

//-------Begin Header Functionality-------

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

add_action('joints_intro', 'open_intro', 2);
add_action('joints_intro', 'do_intro_content');
add_action('joints_intro_content', 'open_intro_content', 2);
add_action('joints_intro_content', 'page_breadcrumbs');
add_action('joints_intro_content', 'get_intro_content');
add_action('joints_intro_content', 'close_intro_content', 50);
add_action('joints_intro_content', 'close_element', 50);
add_action('joints_intro', 'close_element', 50);

function open_intro() {
	?>
	<div <?php apply_filters('intro_class', 'intro') ?>>
	<?php
}
function open_intro_content() {
	?>
	<div class="intro-content">
		<div class="vc_row">
			<div class="vc_column_container vc_col-sm-6">
				<div class="vc_column-inner">
	<?php
}
function do_intro_content() {
	do_action('joints_intro_content');
}
function page_breadcrumbs() {
	global $post;
	if(is_front_page()) {
		return;
	}
	echo '<div class="page-breadcrumbs">';
	$breadcrumbs = array();
	$breadcrumbs[] = get_the_title();
	$ancestors = get_post_ancestors($post);
	foreach($ancestors as $ancestor) {
		$breadcrumbs[] = '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	echo implode(' \\ ', $breadcrumbs);
	echo '</div>';
}
function get_intro_content() {
	$page_for_posts_id = get_option('page_for_posts');
	$page = (is_home() || is_archive() ? $page_for_posts_id : null);
	echo //(is_front_page() ? '' : get_the_title()) .
		(function_exists('get_field') ? get_field('intro_content', $page) : '');
}
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
		if(is_archive() || is_front_page()) {
			joints_page_navi();
		}
	}
	else {
		get_template_part( 'parts/content', 'missing' );
	} 
}

//-------End Loop-------

//-------Begin Entry Header

add_filter('entry_header_class', 'initialize_entry_header_class', 1);
add_filter('entry_header_class', 'do_entry_header_class', 99);

function initialize_entry_header_class() {
	return array('entry-header');
}
function do_entry_header_class($classes) {
	echo 'class="' . implode(' ', $classes) . '"';
}

add_action('joints_entry_header', 'open_entry_header', 1);
add_action('joints_entry_header', 'close_entry_header', 99);

function open_entry_header() {
	?>
	<header <?php apply_filters('entry_header_class'); ?>>
	<?php
}
function close_entry_header() {
	?>
	</header>
	<?php
}

function get_archive_title() {
	?>
	<h2>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<?php  
}

function get_byline() {
	get_template_part( 'parts/content', 'byline' );
}

//-------End Entry Header-------

//-------Begin Entry Content-------

add_action('joints_entry_header', 'open_entry_content', 1);
add_action('joints_entry_header', 'close_entry_content', 99);
add_action('joints_entry_content', 'the_entry_content', 9);

function open_entry_content() {
	?>
	<section class="entry-content" itemprop="articleBody">
	<?php
}
function close_entry_content() {
	?>
	</section> <!-- end article section -->
	<?php
}
function the_entry_content() {
	if(is_archive() || is_front_page()) {
		the_content('<button class="tiny">' . __( 'Read more...', 'jointswp' ) . '</button>');
	}
	else {
		the_content();
	}
}

//-------End Entry Content-------

//-------Begin Sidebar

add_action('joints_sidebar_inner', 'get_primary_sidebar');

function get_primary_sidebar() {
	if ( is_active_sidebar( 'sidebar1' ) ) {
		dynamic_sidebar( 'sidebar1' );
	}
}

//-------End Sidebar
