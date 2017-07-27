<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.1.0.9
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function get_staff_list($options, $settings) {
    // $options = array();
    // if( $settings['id'] == 87 || $settings['id'] == 88 ) {
    //     $args = array('role' => 'staff');
    //     $staff_members = get_users($args);
    //     foreach ($staff_members as $staff_member) {
    //         $options[] = array(
    //             'label' =>  $staff_member->display_name,
    //             'value' =>  $staff_member->display_name,
    //             'calc'  =>  null,
    //             'selected' => 0
    //             );
    //     }
    // }
    return $options;
}

add_filter('ninja_forms_render_options','get_staff_list', 10, 2);

/*********************************************************************************/
function select_menu_for_role($args) {
    $user = belong_get_user_info();
    $role = belong_get_users_role($user->ID);
    if ($args['theme_location'] == 'primary') {
        if ($role == 'Client') {
            $args['menu'] = 'client-top-menu';
        } else {
            $args['menu'] = 'staff-top-menu';
        }
    }
    return $args;
}

add_filter('wp_nav_menu_args', 'select_menu_for_role');

/*********************************************************************************/
function belong_list_events_for_user() {
    ob_start();
    $counter = 0;
    $current_user = belong_get_user_info();
    $assignment_args = array(
    'posts_per_page'   => -1,
    'post_type'        => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        echo "<table>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            $assignment_event = get_field('assignment_select_event', $post->ID);
            //var_dump($assignment_type);
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Events') {
                $counter++;
                $permalink = get_permalink($assignment_event->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" .$permalink. "'>". $post->post_title . "</a></td></tr>";
                // echo "<td>Complete By</td><td>" . get_field('assignment_complete_by', $post->ID) . "</td></tr>";
            }
        }
        echo "</table>";
    }
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');

/*********************************************************************************/
function belong_list_modules_for_user() {
    ob_start();
    $counter = 0;
    $current_user = belong_get_user_info();
    $assignment_args = array(
    'posts_per_page'   => -1,
    'post_type'        => 'assignments'
    );
    
    $module_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        echo "<table>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Modules') {
                $counter++;
                $permalink = get_permalink($post->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" .$permalink. "'>". $post->post_title . "</a></td>";
                echo "<td>Complete By</td><td>" . get_field('assignment_complete_by', $post->ID) . "</td></tr>";
            }
        }
        echo "</table>";
    }
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');


/***********************************
*        HELPER FUNCTIONS          *
************************************/

/***********************************************
Check if current user ID is in the mult-select
array for the particular assignment
***********************************************/
function belong_is_current_user_selected(array $array, $id) {
    foreach ($array as $element) {
        if ($element['ID'] == $id) {
            return true;
        }
    }
    return false;
}

/***********************************************
Get current user
************************************************/
function belong_get_user_info() {
    $current_user = wp_get_current_user();
    if (!($current_user instanceof WP_User))
        return;
    return $current_user;
}

/***********************************************
Get role of current logged in user
- only returns the first one.
************************************************/
function belong_get_users_role($user_id) {
    $user = new WP_User($user_id);
    if (!empty( $user->roles ) && is_array( $user->roles)) {
        return $user->roles[0];
    }
}


?>