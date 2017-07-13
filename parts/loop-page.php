<?php
add_action('joints_entry_content', 'wp_link_pages', 9);

add_action('joints_entry_header', 'get_page_title');
function get_page_title() {
	?>
	<h1 class="page-title"><?php the_title(); ?></h1>
<?php
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> role="article" itemscope itemtype="http://schema.org/WebPage">
						
	<?php 

	do_action('joints_entry_header'); 

	?>

	    <?php  do_action('joints_entry_content'); ?>
						
	<footer class="article-footer">
		
	</footer> <!-- end article footer -->
						    
	<?php comments_template(); ?>
					
</article> <!-- end article -->