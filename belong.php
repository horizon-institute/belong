
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.1.0
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Add a Client user role that can only view their content
function add_roles_on_plugin_activation() {
    add_role( 'client', 'Client', array( 
        'read' => true 
        )
    );

    add_role( 'staff', 'Staff', array( 
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false,   
        )
    );
}

register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


function belong_list_events_for_user() {
    $current_user_ID = get_current_user_id();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'events'
    );

    $posts = get_posts($args);
    foreach ($posts as $post) {
        $event_client = get_field('event_client', $post->ID);
        if ($event_client->ID == $current_user_ID) {
            $event_date = get_field('event_date', $post->ID);
            $event_address = get_field('event_address', $post->ID);
            echo $post->post_title; ?><br /><?php>
            echo $post->post_content; ?><br /><?php>
            echo $event_date; ?><br /><?php>
            echo $event_address;
        }
    }
}

add_shortcode('user_events', 'belong_list_events_for_user');

?>
