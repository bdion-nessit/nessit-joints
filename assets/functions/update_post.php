<?php
function update_post() {
	$output = array();
	if(!is_user_logged_in()) {
		print(json_encode(array(
			'status' => 'error',
			'message' => 'You must be logged in to create warm fuzzies.'
		)));
		die();
	}
	$data = filter_input_array(INPUT_POST, array(
		'data' => array(
			'filter' => FILTER_SANITIZE_STRING,
			'flags' => FILTER_REQUIRE_ARRAY,
		),
	));
	$data = $data['data'];
	
	switch($data['action']) {
		case 'create':
			$postarr = array(
				'post_type' => $data['post_type'],
				'post_status' => 'publish',
			);
			if(!empty($data['post_fields'])) {
				foreach($data['post_fields'] as $i => $field) {
					$postarr[$i] = $field;
				}
			}
			$new = wp_insert_post($postarr);
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