<?php 
get_header();
do_action('joints_before_content');
?>
	
	<div id="content">
	
		<div id="inner-content" class="row">
            
        <?php 
            do_action('joints_secondary_sidebar');
        ?>
	
		    <main id="main" class="large-<?php echo $column_width; ?> medium-<?php echo $column_width; ?> columns" role="main">
	
				<?php 
				do_action('joints_entry'); //Functionality found in core.php
				?>							
			    					
			</main> <!-- end #main -->

		  <?php 
		      do_action('joints_primary_sidebar'); //Main sidebar loads here by default
		  ?>
		    
		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php 
do_action('joints_after_content');
get_footer(); 
