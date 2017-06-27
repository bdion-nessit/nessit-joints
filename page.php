<?php get_header();
do_action('joints_before_content');
?>
	
	<div id="content">
	
		<div id="inner-content" class="row">
	
		    <main id="main" class="large-8 medium-8 columns" role="main">
	
				<?php do_action('joints_entry'); ?>							
			    					
			</main> <!-- end #main -->

		    <?php 
		    add_action('joints_sidebar', 'get_sidebar');
		    do_action('joints_sidebar');
		     ?>
		    
		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php 
do_action('joints_after_content');
get_footer(); 
