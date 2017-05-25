
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.1.3
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
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'events'
    );

    $posts = get_posts($args);
    ob_start();
    foreach ($posts as $post) {
        $event_client = get_field('event_client', $post->ID);
        if ($event_client['ID'] == $current_user->ID) {
            $event_date = get_field('event_date', $post->ID);
            $event_address = get_field('event_address', $post->ID);
            echo '<h3>'.$post->post_title.'</h3>';
            echo '<h5>'.$post->post_content.'</h5>';
            echo '<h5>'.$event_date.'</h5>';
            echo '<h5>'.$event_address.'</h5>';
        }
        echo '<br />';
    }
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');


function belong_list_modules_for_user() {
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'modules'
    );

    $posts = get_posts($args);
    ob_start();
    foreach ($posts as $post) {
        $module_clients = get_field('module_client', $post->ID);

        if (in_array($current_user->ID, $module_clients['ID'])) {
            $module_complete_by = get_field('module_complete_by', $post->ID);
            $module_address = get_field('module_address', $post->ID);
            echo '<h3>'.$post->post_title.'</h3>';
            echo '<h5>'.$post->post_content.'</h5>';
            echo '<h5>'.$module_complete_by.'</h5>';
            echo '<h5>'.$module_address.'</h5>';
        }
        echo '<br />';
    }
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

?>
