<?php
/*
This is the custom post type post template.
If you edit the post type name, you've got
to change the name of this template to
reflect that name change.

i.e. if your custom post type is called
register_post_type( 'bookmarks',
then your single template should be
single-bookmarks.php

*/
?>

<?php 
add_action('joints_primary_sidebar', 'get_sidebar');
get_header(); 
do_action('joints_before_content');
?>
			
<div id="content">

	<div id="inner-content" class="row">
        
        <?php 
            do_action('joints_secondary_sidebar');
        ?>

		<main id="main" class="large-8 medium-8 columns" role="main">
		
		    <?php do_action('joints_entry'); ?>	

		</main> <!-- end #main -->

        <?php 
            do_action('joints_primary_sidebar');
        ?>

	</div> <!-- end #inner-content -->

</div> <!-- end #content -->

<?php 
do_action('joints_after_content');
get_footer(); ?>