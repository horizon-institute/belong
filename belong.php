
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.4.8
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

    /* Access to own input clients with right to edit own.
        Compile reports based accessible clients. */
    add_role( 'agency', 'Agency', array( 
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => false  
        )
    );

    /* Access specific clients (preferable by number not name) review client profile 
        and activity, create reports and print. No editing client info */
    add_role( 'funder', 'Funder', array( 
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
    ob_start();
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    ?>
        <table>
    <?php 
    $module_posts = get_posts($args);
    if($module_posts):
        foreach ($module_posts as $post) :
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if ($assignment_client['ID'] == $current_user->ID && $assignment_type == 'Modules') {
                $assignment_complete_by = get_field('assignment_complete_by', $post->ID);
                $assignment_select_module = get_field('assignment_select_module', $post->ID);
                ?>
                <tr><td><a href="<?php echo get_permalink($post->ID) ?>"><?php echo $post->post_title ?></a></td>; 
                <td><?php echo $assignment_complete_by; ?></td>
                <td><?php echo $assignment_select_module; ?></td></tr>
                <?php 
            }
        endforeach; 
        wp_reset_postdata();
    endif;
        ?>
     </table>
    <?php 
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

?>
