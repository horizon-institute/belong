
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.0.6
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
        $client = get_field('event_client', $post->ID);
        //if ($client == $current_user->ID) {
            $post->post_title;
            $post->post_content;
            get_field('event_date', $post->ID);
            get_field('event_address', $post->ID);
       //}
    }
}

add_shortcode('user_events', 'belong_list_events_for_user');


?>
