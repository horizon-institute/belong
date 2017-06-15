
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.5.5
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*********************************************************************************/
function belong_list_events_for_user() {
    ob_start();
    $current_user = wp_get_current_user();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $event_posts = get_posts($args);  
    if ($event_posts) {
        foreach ($event_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if ($assignment_client['ID'] == $current_user->ID && $assignment_type == 'Events') {
                $assignment_select_event = get_field('assignment_select_event', $post->ID); 
                ?><h4><a href="<?php get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h4><?php
            }
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

    $module_posts = get_posts($args);
    if ($module_posts) {
        foreach ($module_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if ($assignment_client['ID'] == $current_user->ID && $assignment_type == 'Modules') {
                $assignment_complete_by = get_field('assignment_complete_by', $post->ID);
                $assignment_select_module = get_field('assignment_select_module', $post->ID);
                ?><h4><a href="<?php get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></h4>
                <?php
            }
        }
    }
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');


/*********************************************************************************/
function belong_show_user_info_main() {
    $current_user = wp_get_current_user();
    $user_info = get_userdata($current_user);
      echo 'Username: ' . $user_info->user_login . "\n";
      echo 'User roles: ' . implode(', ', $user_info->roles) . "\n";
      echo 'User ID: ' . $user_info->ID . "\n";
}

add_shortcode('user_profile_main', 'belong_show_user_info_main');

?>
