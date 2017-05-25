
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.1.7
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
        'create_users' => true,
        'delete_users' => true,
        'list_users' => true,
        'edit_users' => true   
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
    ?><div class="container"><?php     
    foreach ($posts as $post) {
        $event_client = get_field('event_client', $post->ID);
        if ($event_client['ID'] == $current_user->ID) {
            $event_date = get_field('event_date', $post->ID);
            $event_address = get_field('event_address', $post->ID);
            echo '<div class="row"><h4>'.$post->post_title.'</h4>';
            echo $post->post_content.'<br />';
            echo $event_date.'<br />';
            echo $event_address.'</div>';
        }
        echo '<br />';
    }
    ?></div><?php
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');

Going to
function belong_list_modules_for_user() {
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'modules'
    );

    $posts = get_posts($args);
    ob_start();
    ?><div class="container"><?php
    foreach ($posts as $post) {
        $module_client = get_field('module_client', $post->ID);
        if ($module_client['ID'] == $current_user->ID) {
            $module_complete_by = get_field('module_complete_by', $post->ID);
            $module_address = get_field('module_address', $post->ID);
            echo '<div class="row"><h4>'.$post->post_title.'</h4>';
            echo $post->post_content.'<br />';
            echo $module_complete_by.'<br />';
            echo $module_address.'</div>';Going to
        }
        echo '<br />';
    }
    ?></div><?php
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

?>
