
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.2.2
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Theses roles are further modified by the "Members" role management plugin */
function add_roles_on_plugin_activation() {
    /* Client user role can only view their assigned content. */
    add_role( 'client', 'Client', array( 
        'read' => true 
        )
    );

    /* Staff role can created / edit / delete Modules, Events, Users and Assignments. */
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

    /* External agency will be able to create Modules & Events and do assignments but
    these will have to be approved by a Staff role. */
    add_role( 'agency', 'Agency', array( 
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false  
        )
    );
}

register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );


function belong_list_events_for_user() {
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $posts = get_posts($args);
    ob_start();
    ?><div class="container"><?php     
    foreach ($posts as $post) {
        $assignment_client = get_field('assignment_client', $post->ID);
        $assignment_type = get_field('assignment_type', $post->ID);
        if ($assignment_client['ID'] == $current_user->ID) {
            $assignment_complete_by = get_field('assignment_complete_by', $post->ID);
            echo '<div class="row"><h4>'.$post->post_title.'</h4>';
            echo $assignment_type.' | '.$assignment_complete_by.'<br />';
        }
        echo '<br />';
    }
    ?></div><?php
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
    ?><div class="container"><?php
    foreach ($posts as $post) {
        $module_client = get_field('module_client', $post->ID);
        if ($module_client['ID'] == $current_user->ID) {
            $module_complete_by = get_field('module_complete_by', $post->ID);
            $module_address = get_field('module_address', $post->ID);
            echo '<div class="row"><h4>'.$post->post_title.'</h4>';
            echo $post->post_content.'<br />';
            echo $module_complete_by.'<br />';
            echo $module_address.'</div>';
        }
        echo '<br />';
    }
    ?></div><?php
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

?>
