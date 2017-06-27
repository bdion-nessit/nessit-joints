<?php
/*
Template Name: Full Width (No Sidebar)
*/
?>

<?php 
get_header(); 
do_action('joints_before_content');
?>
			
	<div id="content">
	
		<div id="inner-content" class="row">
	
		    <main id="main" class="large-12 medium-12 columns" role="main">
				
				<?php 

				do_action('joints_entry'); 

				?>							

			</main> <!-- end #main -->
		    
		</div> <!-- end #inner-content -->
	
	</div> <!-- end #content -->

<?php 
do_action('joints_after_content');
get_footer(); 
