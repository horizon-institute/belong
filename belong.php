<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.1.4.8
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
    client_registration_form();
    return ob_get_clean();
}

add_shortcode('client_registration', 'client_registration');

function client_registration_form() {
/* Get User ID and check database to see if exisiting registrtion 
if true then get data from database otherwise display blank form.
*/
    echo '<h4>PERSONAL DETAILS</h4>';
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';

    date_field("pw-registration-date", "DATE"); 
    text_field("pw-client-number", "CLIENT NUMBER");
    staff_select_field("pw-interviewers-name", "INTERVIEWERS NAME");
    staff_select_field("pw-case-owner", "CASE OWNER");
    text_field("pw-first-names", "FIRST NAMES");
    text_field("pw-surname", "SURNAME");
    email_field("pw-email", "EMAIL ADDRESS");
    textarea_field("pw-address", "ADDRESS", "5", "35");
    text_field("pw-postcode", "POST CODE");
    text_field("pw-accomodation-type", "ACCOMODATION TYPE");
    text_field("pw-nationality", "NATIONALITY");
    text_field("pw-nationality-code", "CODE");
    select_field(array("Single","Married","Civil Partnership","Common Law"), "pw-relationship-status", "RELATIONSHIP STATUS");
    text_field("pw-religion", "RELIGION");
    text_field("pw-placeofworship", "PLACE OF WORSHIP");

    echo '<p><input type="submit" name="pw-submitted" value="Send"/></p>';
    echo '</form>';
}

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

/***********************************************
Return a list of staff members
************************************************/
function get_staff_list() {
    $list = array();
    $args = array('role' => 'staff');
    $staff_members = get_users($args);
    foreach ($staff_members as $staff_member) {
        $list[] = array($staff_member->display_name);
    }
    return $list;
}


/***********************************************
 Display the date picker
************************************************/
function datepicker(){ ?>
    <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#date').datepicker({
            dateFormat: 'dd-mm-yy'
        });
    });
    </script>
<?php
}

/***********************************************
 Dynamically populate staff dropdown
************************************************/
function staff_select_field($name, $title) {
    $staff_list = get_staff_list();
    echo '<p>';   
    echo $title . '<br />';
    echo "<select name='". $name . "'><option selected='selected'>choose</option>";
    foreach($staff_list as $item) {
        echo "<option value=" . strtolower($item[0]) . ">" . $item[0] . "</option>";
    }
    echo "</select>";
    echo '</p>';
}

/***********************************************
 Dynamically populate select element from array
************************************************/
function select_field($array, $name, $title) {
    echo '<p>';   
    echo $title . '<br />';    
    echo "<select name='". $name . "'><option selected='selected'>choose</option>";
    foreach($array as $item) {
        echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
    }
    echo "</select>";
    echo '</p>';
}

/***********************************************
 Standard text field
************************************************/
function text_field($name, $title) {
    echo '<p>';   
    echo $title . '<br />'; 
    echo '<input type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST[$name] ) ? esc_attr( $_POST[$name] ) : '' ) . '" size="40" />';
    echo '</p>';
}

/***********************************************
 jquery date picker field
************************************************/
function date_field($name, $title) {
    datepicker();
    echo '<p>';
    echo $title . '<br />'; 
    echo '<input  id="date" name="' . $name . '" />';
    echo '</p>';  
}

/***********************************************
 email field with validation
************************************************/
function email_field($name, $title) {
    echo '<p>';
    echo $title . '<br />'; 
    echo '<input type="email" name="' . $name . '" value="' . ( isset( $_POST[$name] ) ? esc_attr( $_POST[$name] ) : '' ) . '" size="40" />';
    echo '</p>';  
}

/***********************************************
 textarea field 
************************************************/
function textarea_field($name, $title, $rows, $columns) {
    echo '<p>';
    echo $title . '<br />'; 
    echo '<textarea rows="' . $rows . '" cols="' . $columns . '" name="' . $name . '">' . ( isset( $_POST[$name] ) ? esc_attr( $_POST[$name] ) : '' ) . '</textarea>';
    echo '</p>';  
}


?>