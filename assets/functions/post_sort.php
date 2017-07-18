<?php
function do_post_sort() {
    $data = filter_input_array(INPUT_POST, array(
        'data' => array(
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_ARRAY,
        ),
    ));
    $data = $data['data'];
    $args = array();
    foreach($data['query_data'] as $i => $item) {
        $args[$i] = $item;
    }
    $query1 = new WP_Query($args);
    $output = array();
    if(!empty($data['callback'])) {
        $output['content'] = $data['callback']($query1);
        $output['type'] = 'string';
    }
    
    print(json_encode($output));
    die();
}