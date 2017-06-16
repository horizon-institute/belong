
<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.0.7.4
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/*********************************************************************************/
function belong_list_events_for_user() {
    ob_start();
    $counter = 0;
    $current_user = belong_get_user_info();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $event_posts = get_posts($args);  
    if ($event_posts) {
        echo "<table>";
        foreach ($event_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if (is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Events') {
                $counter++;
                $permalink = get_permalink($post->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" .$permalink. "'>". $post->post_title . "</a></td>";
                echo "<td>Complete By</td><td>" . get_field('assignment_complete_by', $post->ID) . "</td></tr>";
            }
        }
        echo "</table>";echo "array ID: " . $element->ID . "<br />";
    }
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');


/*********************************************************************************/
function belong_list_modules_for_user() {
    ob_start();
    $counter = 0;
    $current_user = belong_get_user_info();
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'assignments'
    );

    $module_posts = get_posts($args);
    if ($module_posts) {
        echo "<table>";
        foreach ($module_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if (is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Modules') {
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

/*********************************************************************************
Check if current user ID is in the mult-select array for the particulat assignment
**********************************************************************************/
function is_current_user_selected(array $array, $id) {
    foreach ($array as $element) {

        echo "array ID: " . $element->ID . "<br />";
        echo "login ID: " . $id . "<br />";

        if ($element->ID == $id) {
            echo "Should return true";
            return true;
        }
    }
    return false;
}


/*********************************************************************************/
function belong_show_user_info_main() {
    ob_start();
    $user_info = belong_get_user_info();
    echo "<table><tr><td>Display Name</td><td>" . $user_info->display_name . "</td></tr>";
    echo "<tr><td>First Name</td><td>" . $user_info->user_firstname . "</td></tr>";
    echo "<tr><td>Last Name</td><td>" . $user_info->user_lastname . "</td></tr>";
    echo "<tr><td>Email</td><td>" . $user_info->user_email . "</td></tr></table>";

    /*also need to include custom user fields:
    client number / date of registration
    interviewer / case owner
    gender

    address
    telephone
    postcode
    accomodation type
    nationality / code
    married /civil partenership / common law / single
    religion / place of worship
    spouse partner name / client number / uk resident / did they travel with you?

    */
    return ob_get_clean();
}

add_shortcode('user_profile_main', 'belong_show_user_info_main');

function belong_get_user_info() {
    $current_user = wp_get_current_user(); 
    if (!($current_user instanceof WP_User)) 
        return; 
    return $current_user; 
}

?>
