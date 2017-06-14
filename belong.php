
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.4.3
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

/*********************************************************************************/
function belong_list_events_for_user() {
    ob_start();
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $event_posts = get_posts($args);  
    foreach ($event_posts as $post) {
        $assignment_client = get_field('assignment_client', $post->ID);
        $assignment_type = get_field('assignment_type', $post->ID);
        if ($assignment_client['ID'] == $current_user->ID && $assignment_type == 'Events') {
            $assignment_select_event = get_field('assignment_select_event', $post->ID); 
            ?><h4><a href="<?php get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h4><?php
        }
    }
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');


/*********************************************************************************/
function belong_list_modules_for_user() {
    $max_per_row = 5;
    $item_count = 0;

    ob_start();
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $module_posts = get_posts($args);
    echo "<table>";
    echo "<tr>";
    foreach ($module_posts as $post) {
    if ($item_count == $max_per_row) {
        echo "</tr><tr>";
        $item_count = 0;
    }
        $assignment_client = get_field('assignment_client', $post->ID);
        $assignment_type = get_field('assignment_type', $post->ID);
        if ($assignment_client['ID'] == $current_user->ID && $assignment_type == 'Modules') {
            $assignment_complete_by = get_field('assignment_complete_by', $post->ID);
            $assignment_select_module = get_field('assignment_select_module', $post->ID);
            echo"<td><h4><a href=".get_permalink($post->ID).">".$post->post_title."</a></h4></td>"; 
            echo"<td><h4>".$assignment_complete_by."</h4></td>"; 
            echo"<td><h4>".$assignment_select_module."</h4></td>"; 
        }
    }
    echo "</tr>";
    echo "</table>";
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

?>
