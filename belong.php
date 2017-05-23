
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.0.8
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
    global $current_user;
    $posts = get_posts();
    foreach ($posts as $post) {
        $event_client = get_field('event_client', $post->ID);
        $event_date = get_field('event_date', $post->ID);
        $event_address = get_field('event_address', $post->ID);
        if ($event_client->ID == $current_user->ID) {
            echo $post->post_title;
            echo $post->post_content;
            echo $event_date;
            echo $event_address;
        }
    }
}

add_shortcode('user_events', 'belong_list_events_for_user');


?>
