<?php
function update_post() {
	$output = array(); //Initialize array of output
	
	//If user is not logged in, return with an error and without creating the post
	if(!is_user_logged_in()) {
		print(json_encode(array(
			'status' => 'error',
			'message' => 'You must be logged in to create warm fuzzies.'
		)));
		die();
	}
	
	//Get and sanitize data
	$data = filter_input_array(INPUT_POST, array(
		'data' => array(
			'filter' => FILTER_SANITIZE_STRING,
			'flags' => FILTER_REQUIRE_ARRAY,
		),
	));
	
	$data = $data['data']; //Simplify data variable
	
	switch($data['action']) {
		case 'create':
			//Array of fields to pass as an argument to wp_insert_post
			$postarr = array(
				'post_type' => $data['post_type'],
				'post_status' => 'publish',
			);
			
			//For each post field set, add it to the $postarr
			if(!empty($data['post_fields'])) {
				foreach($data['post_fields'] as $i => $field) {
					$postarr[$i] = $field;
				}
			}
			
			$new = wp_insert_post($postarr); //Create post based on values passed to date
			
			//If post created successfully
			if($new > 0) {
				$output['status'] = 'success';
				$output['message'] = 'Post successfully created!';
			}
			else {
				$output['status'] = 'error';
				$output['message'] = 'Error creating post';
			}
			break;
	}
	
	print(json_encode($output));
	die();
}