<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.1.3.6
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
    echo '<h2>PERSONAL DETAILS</h2>';
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';

    echo '<p>';
    echo 'DATE<br />';
    echo '<input  id="date" name="pw-registration-date" />';
    echo '</p>';     

    echo '<p>';   
    echo 'CLIENT NUMBER<br />';
    echo '<input type="text" name="pw-client-number" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["pw-client-number"] ) ? esc_attr( $_POST["pw-client-number"] ) : '' ) . '" size="40" />';
    echo '</p>';

    $staff_list = get_staff_list();
    var_dump($staff_list);

    echo '<p>';   
    echo 'INTERVIEWERS NAME<br />';
    echo populate_select($staff_list, "pw-interviewers-name");
    echo '</p>';

    echo '<p>';   
    echo 'CASE OWNER<br />';
    echo populate_select($staff_list, "pw-case-owner");
    echo '</p>';

    echo '<p>';
    echo 'FIRST NAMES<br />';
    echo '<input type="text" name="pw-first-names" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["pw-first-names"] ) ? esc_attr( $_POST["pw-first-names"] ) : '' ) . '" size="40" />';
    echo '</p>';

    echo '<p>';
    echo 'SURNAME<br />';
    echo '<input type="text" name="pw-surname" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["pw-surname"] ) ? esc_attr( $_POST["pw-surname"] ) : '' ) . '" size="40" />';
    echo '</p>';  

    echo '<p>';
    echo 'SURNAME<br />';
    echo '<input type="text" name="pw-telephone" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["pw-telephone"] ) ? esc_attr( $_POST["pw-telephone"] ) : '' ) . '" size="40" />';
    echo '</p>';       

    echo '<p>';
    echo 'EMAIL ADDRESS<br />';
    echo '<input type="email" name="pw-email" value="' . ( isset( $_POST["pw-email"] ) ? esc_attr( $_POST["pw-email"] ) : '' ) . '" size="40" />';
    echo '</p>';

    echo '<p>';
    echo 'ADDRESS<br />';
    echo '<textarea rows="10" cols="35" name="pw-address">' . ( isset( $_POST["pw-address"] ) ? esc_attr( $_POST["pw-address"] ) : '' ) . '</textarea>';
    echo '</p>';
 
    echo '<p>';
    echo 'POST CODE<br />';
    echo '<input type="text" name="pw-postcode" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["pw-postcode"] ) ? esc_attr( $_POST["pw-postcode"] ) : '' ) . '" size="40" />';
    echo '</p>';

    echo '<p>';
    echo 'ACCOMODATION TYPE<br />';
    echo '<input type="text" name="pw-accomodation-type" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["pw-accomodation-type"] ) ? esc_attr( $_POST["pw-accomodation-type"] ) : '' ) . '" size="40" />';
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
}


/* Dynamically populate select element from array */
function populate_select($array, $name) {
  echo "<select name='". $name . "'><option selected='selected'>choose</option>";
  foreach($array as $item) {
    echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
   }
   echo "</select>";
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
    $args = array('role' => 'staff');
    $staff_members = get_users($args);
    foreach ($staff_members as $staff_member) {
        $list[] = array($staff_member->display_name);
    }
    return $list;
}



?>