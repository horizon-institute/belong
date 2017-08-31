<?php

/**
 * Plugin Name: Belong
 * Plugin URI: http://belong-horizon.cloudapp.net
 * Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
 * Description: Custom functionality for Belong Nottingham CRM
<<<<<<< HEAD
 * Version: 0.1.7.5
=======
 * Version: 0.1.7.4
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
 * Author: Javid Yousaf
 * License: GPL3
 */

// Prevent direct access
defined('ABSPATH') or die('No script kiddies please!');

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
wp_enqueue_script('prefix_bootstrap');

wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
wp_enqueue_style('prefix_bootstrap');
/*********************************************************************************/
function client_registration_form()
{
    $id = $_GET['clientID'];
    
    ob_start();
<<<<<<< HEAD
    echo '<div class="container">';
    echo '<h4>PERSONAL DETAILS</h4>';
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '<div class="row">';
    date_field("pw-registration-date", "DATE", "reg_date", "6");
    text_field("pw-client-number", "CLIENT NUMBER", "6");
    echo '</div>';
    echo '<div class="row">';
    staff_select_field("pw-interviewers-name", "INTERVIEWERS NAME", "6");
    staff_select_field("pw-case-owner", "CASE OWNER", "6");
    echo '</div>';
    echo '<div class="row">';
    echo '<fieldset disabled>';
    user_field("pw-display_name", "DISPLAY NAME", "display_name", $id, "6");
    user_field("pw-email", "EMAIL ADDRESS", "user_email", $id, "6");
    echo '</fieldset>';
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Male","Female"), "pw-client_gender", "GENDER", "6");
    text_field("pw-client-telephone", "TELEPHONE NUMBER", "6");
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-client_dob", "DATE OF BIRTH", "client_dob", "12");
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-address", "ADDRESS", "5", "35", "12");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-postcode", "POST CODE", "6");
    text_field("pw-accomodation-type", "ACCOMODATION TYPE", "6");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-nationality", "NATIONALITY", "6");
    text_field("pw-nationality-code", "CODE", "6");
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Single","Married","Civil Partnership","Common Law"), "pw-relationship-status", "RELATIONSHIP STATUS", "12");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-religion", "RELIGION", "6");
    text_field("pw-placeofworship", "PLACE OF WORSHIP", "6");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-spouse-name", "SPOUSE/PARTNER NAME", "6");
    text_field("pw-spouse-cn", "SPOUSE/PARTNER CLIENT NUMBER", "6");
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-spouse-uk", "UK RESIDENT", "6");
    select_field(array("Yes","No"), "pw-spouse-travel", "DID THEY TRAVEL TO THE UK WITH YOU", "6");
    echo '</div>';  

=======
    echo '<h4>PERSONAL DETAILS</h4>';
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '<table>';
    echo '<tr>';
    date_field("pw-registration-date", "DATE", "reg_date");
    text_field("pw-client-number", "CLIENT NUMBER");
    echo '</tr>';
    echo '<tr>';
    staff_select_field("pw-interviewers-name", "INTERVIEWERS NAME");
    staff_select_field("pw-case-owner", "CASE OWNER");
    echo '</tr>';
    echo '<fieldset disabled>';
    echo '<tr>';
    user_field("pw-display_name", "DISPLAY NAME", "display_name", $id);
    user_field("pw-email", "EMAIL ADDRESS", "user_email", $id);
    echo '</tr>';
    echo '</fieldset>';
    echo '<tr>';
    select_field(array("Male","Female"), "pw-client_gender", "GENDER");
    text_field("pw-client-telephone", "TELEPHONE NUMBER");
    echo '</tr>';
    echo '<tr>';
    date_field("pw-client_dob", "DATE OF BIRTH", "client_dob");
    echo '</tr>';
    echo '<tr>';
    textarea_field("pw-address", "ADDRESS", "5", "35");
    echo '</tr>';
    echo '<tr>';
    text_field("pw-postcode", "POST CODE");
    text_field("pw-accomodation-type", "ACCOMODATION TYPE");
    echo '<tr>';
    text_field("pw-nationality", "NATIONALITY");
    text_field("pw-nationality-code", "CODE");
    echo '</tr>';
    echo '<tr>';
    select_field(array("Single","Married","Civil Partnership","Common Law"), "pw-relationship-status", "RELATIONSHIP STATUS");
    echo '</tr>';
    echo '<tr>';
    text_field("pw-religion", "RELIGION");
    text_field("pw-placeofworship", "PLACE OF WORSHIP");
    echo '</tr>';
    echo '<tr>';
    text_field("pw-spouse-name", "SPOUSE/PARTNER NAME");
    text_field("pw-spouse-cn", "SPOUSE/PARTNER CLIENT NUMBER");
    echo '</tr>';
    echo '<tr>';
    select_field(array("Yes","No"), "pw-spouse-uk", "UK RESIDENT");
    select_field(array("Yes","No"), "pw-spouse-travel", "DID THEY TRAVEL TO THE UK WITH YOU");
    echo '</tr>';
    
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
    child_field("1");
    child_field("2");
    child_field("3");
    child_field("4");
<<<<<<< HEAD

    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<input type="submit" name="pw-submitted" value="Save"/>';
    echo '</div>';
    echo '</div>';   
    echo '</form>';
    echo '</div>';
=======
    echo '</table>';
    
    echo '<p><input type="submit" name="pw-submitted" value="Save"/></p>';
    echo '</form>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
    return ob_get_clean();
}

add_shortcode('client_registration', 'client_registration_form');

/*********************************************************************************/
function client_view_form()
{ 
    $current_user = belong_get_current_user();
    $id           = $current_user->ID;
    
    ob_start();
    echo '<h4>PERSONAL DETAILS</h4>';
    echo '<form>';
    echo '<fieldset disabled>';
    echo '<table>';
    date_field("pw-registration-date", "DATE", "reg_date");
    text_field("pw-client-number", "CLIENT NUMBER");
    staff_select_field("pw-interviewers-name", "INTERVIEWERS NAME");
    staff_select_field("pw-case-owner", "CASE OWNER");
    user_field("pw-display_name", "DISPLAY NAME", "display_name", $id);
    user_field("pw-email", "EMAIL ADDRESS", "user_email", $id);

    select_field(array("Male","Female"), "pw-client_gender", "GENDER");
    text_field("pw-client-telephone", "TELEPHONE NUMBER");
    date_field("pw-client_dob", "DATE OF BIRTH", "client_dob");

    textarea_field("pw-address", "ADDRESS", "5", "35");
    text_field("pw-postcode", "POST CODE");
    text_field("pw-accomodation-type", "ACCOMODATION TYPE");
    text_field("pw-nationality", "NATIONALITY");
    text_field("pw-nationality-code", "CODE");
    select_field(array(
        "Single",
        "Married",
        "Civil Partnership",
        "Common Law"
    ), "pw-relationship-status", "RELATIONSHIP STATUS");
    text_field("pw-religion", "RELIGION");
    text_field("pw-placeofworship", "PLACE OF WORSHIP");

    text_field("pw-spouse-name", "SPOUSE/PARTNER NAME");
    text_field("pw-spouse-cn", "SPOUSE/PARTNER CLIENT NUMBER");
    select_field(array("Yes","No"), "pw-spouse-uk", "UK RESIDENT");
    select_field(array("Yes","No"), "pw-spouse-travel", "DID THEY TRAVEL TO THE UK WITH YOU");

    
    child_field("1");
    child_field("2");
    child_field("3");
    child_field("4");
    echo '</table>';
    echo '</fieldset>';
    echo '</form>';
    return ob_get_clean();
}

add_shortcode('client_view', 'client_view_form');

/*********************************************************************************/
function belong_list_events_for_user()
{
    ob_start();
    $counter         = 0;
    $current_user    = belong_get_current_user();
    $assignment_args = array(
        'posts_per_page' => -1,
        'post_type' => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        echo "<table>";
        echo "<tr><td></td><td>Event Name</td><td> Date & Time</td></tr>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type   = get_field('assignment_type', $post->ID);
            
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Events') {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $date             = new DateTime($event_datetime);
                $counter++;
                $permalink = get_permalink($assignment_event->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" . $permalink . "'>" . $assignment_event->post_title . "</a></td>";
                echo "<td>" . $date->format('F j, Y g:i a') . "</td></tr>";
            }
        }
        echo "</table>";     
    }
    return ob_get_clean();
}

add_shortcode('user_events', 'belong_list_events_for_user');

/*********************************************************************************/
function belong_list_modules_for_user()
{
    ob_start();
    $counter         = 0;
    $current_user    = belong_get_current_user();
    $assignment_args = array(
        'posts_per_page' => -1,
        'post_type' => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        echo "<table>";
        echo "<tr><td></td><td>Module Name</td><td>Complete By</td></tr>";
        foreach ($assignment_posts as $post) {
            $assignment_client = get_field('assignment_client', $post->ID);
            $assignment_type   = get_field('assignment_type', $post->ID);
            if (belong_is_current_user_selected($assignment_client, $current_user->ID) && $assignment_type == 'Modules') {
                $assignment_module = get_field('assignment_select_module', $post->ID);
                $assignment_date   = get_field('assignment_complete_by', $post->ID);
                $date              = new DateTime($assignment_date);
                $counter++;
                $permalink = get_permalink($assignment_module->ID);
                echo "<tr><td>" . $counter . "</td><td><a href='" . $permalink . "'>" . $assignment_module->post_title . "</a></td>";
                echo "<td>" . $date->format('j M Y') . "</td></tr>";
            }
        }
        echo "</table>";
    }
    return ob_get_clean();
}

add_shortcode('user_modules', 'belong_list_modules_for_user');

/**********************************************************
 * Displays a list of clients with links to their profiles
 ***********************************************************/
function belong_list_clients()
{
    ob_start();
    $counter   = 0;
    $user_args = array(
        'role' => 'Client'
    );
    
    $clients = get_users($user_args);
    echo "<table>";
    echo "<tr><td></td><td>Client Name</td></tr>";
    foreach ($clients as $client) {
        $counter++;
        echo "<tr><td>" . $counter . "</td><td><a href=" . esc_url(add_query_arg('clientID', $client->ID, site_url('/client-profile'))) . ">" . esc_html($client->display_name) . "</a></td></tr>";
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
function belong_send_SMS($message, $numbers)
{
    global $sms;
    $sms->to  = array(
        $numbers
    );
    $sms->msg = $message;
    $sms->SendSMS();
}

/***********************************************
Check if current user ID is in the mult-select
array for the particular assignment
***********************************************/
function belong_is_current_user_selected(array $array, $id)
{
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
function belong_get_current_user()
{
    $current_user = wp_get_current_user();
    if (!($current_user instanceof WP_User))
        return;
    return $current_user;
}

/***********************************************
Get user object by method specified
************************************************/
function belong_get_user_by($field, $value)
{
    $userdata = WP_User::get_data_by($field, $value);
    if (!$userdata)
        return false;
    $user = new WP_User;
    $user->init($userdata);
    return $user;
}


/***********************************************
Get role of current logged in user
- only returns the first one.
************************************************/
function belong_get_users_role($user_id)
{
    $user = new WP_User($user_id);
    if (!empty($user->roles) && is_array($user->roles)) {
        return $user->roles[0];
    }
}

/***********************************************
Return a list of staff members
************************************************/
function get_staff_list()
{
    $list          = array();
    $args          = array(
        'role' => 'staff'
    );
    $staff_members = get_users($args);
    foreach ($staff_members as $staff_member) {
        $list[] = array(
            $staff_member->display_name
        );
    }
    return $list;
}


/***********************************************
Display the date picker
************************************************/
function datepicker($date_id)
{
?>
   <script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#"+<?php echo json_encode($date_id); ?>).datepicker({
            changeYear: true,
            yearRange: "-100:+0",
            dateFormat: 'dd-mm-yy'
        });
    });
    </script>
<?php
}

/***********************************************
Dynamically populate staff dropdown
************************************************/
<<<<<<< HEAD
function staff_select_field($name, $title, $col)
{
    $staff_list = get_staff_list();
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
=======
function staff_select_field($name, $title)
{
    $staff_list = get_staff_list();
    echo '<td>';
    echo $title . '<br />';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
    echo "<select name='" . $name . "'><option selected='selected'>choose</option>";
    foreach ($staff_list as $item) {
        echo "<option value=" . strtolower($item[0]) . ">" . $item[0] . "</option>";
    }
    echo "</select>";
<<<<<<< HEAD
    echo '</div>';
=======
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}

/***********************************************
Dynamically populate select element from array
************************************************/
<<<<<<< HEAD
function select_field($array, $name, $title, $col)
{
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "'><option selected='selected'>choose</option>";
=======
function select_field($array, $name, $title)
{
    echo '<td>';
    echo $title . '<br />';
    echo "<select name='" . $name . "'><option selected='selected'>choose</option>";
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
    foreach ($array as $item) {
        echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
    }
    echo "</select>";
<<<<<<< HEAD
    echo '</div>';
=======
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}


/***********************************************
Multi select element field from array
************************************************/
<<<<<<< HEAD
function select_multiple($array, $name, $title, $col)
{
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "' multiple='multiple'><option selected='selected'>choose</option>";
=======
function select_multiple($array, $name, $title)
{
    echo '<td>';
    echo $title . '<br />';
    echo "<select name='" . $name . "' multiple='multiple'><option selected='selected'>choose</option>";
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
    foreach ($array as $item) {
        echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
    }
    echo "</select>";
<<<<<<< HEAD
    echo '</div>';
=======
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}

/***********************************************
Standard text field
************************************************/
<<<<<<< HEAD
function text_field($name, $title, $col)
{
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</div>';
=======
function text_field($name, $title)
{
    echo '<td>';
    echo $title . '<br />';
    echo '<input type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}


/***********************************************
User text field
************************************************/
<<<<<<< HEAD
function user_field($name, $title, $field_name, $id, $col)
{
    $user  = belong_get_user_by("ID", $id);
    $field = $user->$field_name;
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($field) ? esc_attr($field) : '') . '" size="50" />';
    echo '</div>';
=======
function user_field($name, $title, $field_name, $id)
{
    $user  = belong_get_user_by("ID", $id);
    $field = $user->$field_name;
    echo '<td>';
    echo $title . '<br />';
    echo '<input type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($field) ? esc_attr($field) : '') . '" size="50" />';
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}


/***********************************************
jquery date picker field
************************************************/
<<<<<<< HEAD
function date_field($name, $title, $date_id, $col)
{
    datepicker($date_id);
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input  class="form-control" id="' . $date_id . '" name="' . $name . '" />';
    echo '</div>';
=======
function date_field($name, $title, $date_id)
{
    datepicker($date_id);
    echo '<td>';
    echo $title . '<br />';
    echo '<input  id="' . $date_id . '" name="' . $name . '" />';
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}

/***********************************************
email field with validation
************************************************/
<<<<<<< HEAD
function email_field($name, $title, $col)
{
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="email" name="' . $name . '" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</div>';
=======
function email_field($name, $title)
{
    echo '<td>';
    echo $title . '<br />';
    echo '<input type="email" name="' . $name . '" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}

/***********************************************
textarea field 
************************************************/
<<<<<<< HEAD
function textarea_field($name, $title, $rows, $columns, $col)
{
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<textarea class="form-control" rows="' . $rows . '" cols="' . $columns . '" name="' . $name . '">' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '</textarea>';
    echo '</div>';
=======
function textarea_field($name, $title, $rows, $columns)
{
    echo '<td>';
    echo $title . '<br />';
    echo '<textarea rows="' . $rows . '" cols="' . $columns . '" name="' . $name . '">' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '</textarea>';
    echo '</td>';
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}

function child_field($child_no)
{
<<<<<<< HEAD
    echo '<div class="row">';
    text_field("pw_child_name_" . $child_no, "NAME", "6");
    date_field("pw_child_dob_" . $child_no, "DOB", "child_dob_" . $child_no, "6");
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw_child_uk_" . $child_no, "UK?", "6");
    text_field("pw_child_cn_" . $child_no, "CN", "6");
    echo '</div>';
=======
    echo '<tr><td>';
    text_field("pw_child_name_" . $child_no, "NAME");
    echo '</td><td>';
    date_field("pw_child_dob_" . $child_no, "DOB", "child_dob_" . $child_no);
    echo '</td></tr><tr><td>';
    select_field(array("Yes","No"), "pw_child_uk_" . $child_no, "UK?");
    echo '</td><td>';
    text_field("pw_child_cn_" . $child_no, "CN");
    echo '</td></tr>';  
>>>>>>> 4ecb024663ccf461735308f4e5c10e01f96df2ee
}


?>layout grid