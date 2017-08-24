<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.1.3.1
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');



/*********************************************************************************/
function client_registration() {
    ob_start();
    datepicker();
    client_registration_form();
    return ob_get_clean();
}

add_shortcode('client_registration', 'client_registration');

function client_registration_form() {
/* Get User ID and check database to see if exisiting registrtion 
if true then get data from database otherwise display blank form.
*/
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Your Name (required) <br />';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Email (required) <br />';
    echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Subject (required) <br />';
    echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Your Message (required) <br />';
    echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p>';
    echo 'Date<br />';
    echo '<input  id="date" name="belong-date">' . ( isset( $_POST["belong-date"] ) ? esc_attr( $_POST["belong-date"] ) : '' ) . '< />';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Send"/></p>';
    echo '</form>';

}

/*********************************************************************************/
function datepicker(){ ?>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    });
    </script>
<?php
} // close add_datepicker_in_footer() here
//add an action to call add_datepicker_in_footer function
add_action('wp_footer','datepicker',10);


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
        echo "<tr><td></td><td>Event Name</td><td> Date & Time</td></tr>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Events') {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime = get_field('event_date', $assignment_event->ID);
                $date = new DateTime($event_datetime);
                $counter++;
                $permalink = get_permalink($assignment_event->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" .$permalink. "'>". $assignment_event->post_title . "</a></td>";
                echo "<td>" . $date->format('F j, Y g:i a') . "</td></tr>";
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
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        echo "<table>";
        echo "<tr><td></td><td>Module Name</td><td>Complete By</td></tr>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type = get_field('assignment_type', $post->ID);
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Modules') {
                $assignment_module = get_field('assignment_select_module', $post->ID);
                $assignment_date = get_field('assignment_complete_by', $post->ID);
                $date = new DateTime($assignment_date);
                $counter++;
                $permalink = get_permalink($assignment_module->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" .$permalink. "'>". $assignment_module->post_title . "</a></td>";
                echo "<td>" . $date->format('j M Y') . "</td></tr>";
            }
        }
        echo "</table>";
    }
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

/*********************************************************************************/
function belong_list_clients() {
    ob_start();
    $counter = 0;
    $user_args = array(
    'role' => 'Client'
    );

    $clients = get_users( $user_args );   
    echo "<table>";
    echo "<tr><td></td><td>Client Name</td></tr>";
    foreach ( $clients as $client ) {
        $counter++;
        echo "<tr><td>" . $counter . "</td><td>". esc_html( $client->display_name ) ."</td></tr>";
        //permalink to clients profile -> this will involve comparing 
        //the user ID's and retrieving the latest submissions
    }
    echo "</table>";
    return ob_get_clean();
}

add_shortcode('belong_clients', 'belong_list_clients');


/***********************************
*        HELPER FUNCTIONS          *
************************************/

/**************************************************************
* Send SMS to users number. Need to restrict lenght of message
* $numbers is an array. $message is the message to send.
***************************************************************/
function belong_send_SMS($message, $numbers) {
    global $sms;
    $sms->to = array($numbers);
    $sms->msg = $message;
    $sms->SendSMS();
}

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



// function get_staff_list($options, $settings) {
//     $options = array();
//     if( $settings['id'] == 87 || $settings['id'] == 88 ) {
//         $args = array('role' => 'staff');
//         $staff_members = get_users($args);
//         foreach ($staff_members as $staff_member) {
//             $options[] = array(
//                 'label' =>  $staff_member->display_name,
//                 'value' =>  $staff_member->display_name,
//                 'calc'  =>  null,
//                 'selected' => 0
//                 );
//         }
//     }
//     return $options;
// }

// add_filter('ninja_forms_render_options','get_staff_list', 10, 2);


?>