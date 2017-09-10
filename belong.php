<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.2.3.4
* Author: Javid Yousaf
* License: GPL3
*/

// Prevent direct access
defined('ABSPATH') or die('No script kiddies please!');

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

wp_register_script('prefix_bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
wp_enqueue_script('prefix_bootstrap');

wp_register_style('prefix_bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
wp_enqueue_style('prefix_bootstrap');
/*********************************************************************************/

function client_registration_form() {
    ob_start();
    $id = $_GET['clientID'];
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post" id="tab">';
    echo '<div class="form-group">';
    client_registration($id);
    echo '</div>';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="form-group">';
    echo '<button type="submit" class="btn btn-default" name="pw-personal_form_submit">UPDATE</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    return ob_get_clean();
}

add_shortcode('client_registration', 'client_registration_form');
/*********************************************************************************/

function client_view_form() {
    $current_user = belong_get_current_user();
    $id           = $current_user->ID;
    ob_start();
    echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post" id="tab">';
    echo '<fieldset disabled>';
    client_registration($id);
    echo '</fieldset>';
    echo '</form>';
    return ob_get_clean();
}

add_shortcode('client_view', 'client_view_form');
/*********************************************************************************/

function client_registration($id) {
    echo '<ul class="nav nav-tabs">';
    echo '<li class="active">';
    echo '<a href="#personal" data-toggle="tab">PERSONAL DETAILS</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#education" data-toggle="tab">EDUCATION</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#language" data-toggle="tab">LANGUAGE</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#immigration" data-toggle="tab">IMMIGRATION STATUS</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#financial" data-toggle="tab">FINANCIAL NEEDS</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#work" data-toggle="tab">LOOKING FOR WORK</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#work_experience" data-toggle="tab">WORK EXPERIENCE</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#health" data-toggle="tab">HEALTH & WELLBEING</a>';
    echo '</li>';
    echo '<li>';
    echo '<a href="#additional" data-toggle="tab">ADDITIONAL INFORMATION</a>';
    echo '</li>';
    echo '</ul>';
    
    echo '<div class="tab-content">';
    echo '<div class="tab-pane active" id="personal">';
    personal_form($id);
    echo '</div>';
    echo '<div class="tab-pane fade" id="education">';
    education_form();
    echo '</div>';
    echo '<div class="tab-pane fade" id="language">';
    language_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="immigration">';
    immigration_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="financial">';
    financial_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="work">';
    work_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="work_experience">';
    work_experience_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="health">';
    health_form();
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="additional">';
    additional_form();
    echo '</div>';
    
    echo '</div>';
}
/*********************************************************************************/

function personal_form($id) {
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
    text_field("pw-nationality", "NATIONALITY", "4");
    text_field("pw-nationality-code", "CODE", "4");
    select_field(array("Single","Married","Civil Partnership","Common Law"), "pw-relationship-status", "RELATIONSHIP STATUS", "4");
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
    
    echo '<div class="row">';
    children();
    echo '</div>';
    
    
}
/*********************************************************************************/

function education_form() {
    echo '<div class="row">';
    select_multiple(array("Primary School","Secondary School","6th Form College","College","University","Other"), "pw-education-attended", "HAVE YOU EVER ATTENDED ANY OF THE FOLLOWING?", "6");
    text_field("pw-education-other", "OTHER", "6");
    echo '</div>';
    echo '<div class="row">';
    echo '<h4>WHAT FORMAL QUALIFICATIONS DO YOU HAVE AND HAVE THEY BEEN RECOGNISED?</h4>';
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-1", "QUALIFICATION", "10");
    select_field(array("Yes","No"), "pw-education-recognised-1", "RECOGNISED?", "2");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-2", "QUALIFICATION", "10");
    select_field(array("Yes","No"), "pw-education-recognised-2", "RECOGNISED?", "2");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-3", "QUALIFICATION", "10");
    select_field(array("Yes","No"), "pw-education-recognised-3", "RECOGNISED?", "2");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-4", "QUALIFICATION", "10");
    select_field(array("Yes","No"), "pw-education-recognised-4", "RECOGNISED?", "2");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-5", "QUALIFICATION", "10");
    select_field(array("Yes","No"), "pw-education-recognised-5", "RECOGNISED?", "2");
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-education-not-recognised", "IF THEY HAVE NOT BEEN RECOGNISED PLEASE TELL US WHY", "5", "35", "12");
    echo '</div>';
}
/*********************************************************************************/

function language_form() {
    echo '<div class="row">';
    text_field("pw-language-primary", "WHAT IS YOUR PRIMARY LANGUAGE?", "8");
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-1", "ABILITY", "4");
    echo '</div>';
    echo '<div class="row">';
    echo '<h4>DO YOU SPEAK ANY OTHER LANGUAGES?</h4>';
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-2", "WHAT IS YOUR PRIMARY LANGUAGE?", "8");
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-2", "ABILITY", "4");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-3", "WHAT IS YOUR PRIMARY LANGUAGE?", "8");
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-3", "ABILITY", "4");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-4", "WHAT IS YOUR PRIMARY LANGUAGE?", "8");
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-4", "ABILITY", "4");
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-5", "WHAT IS YOUR PRIMARY LANGUAGE?", "8");
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-5", "ABILITY", "4");
    echo '</div>';

    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-education-translate", "ARE YOU PREPARED TO ASSIST IN TRANSLATION?", "6");
    select_field(array("Yes","No"), "pw-language-assistance", "DO YOU NEED ASSISTANCE WITH ENGLISH?", "6");
    echo '</div>';
    
    echo '<div class="row">';
    echo '<h4>IF YOU ARE ALREADY ON A COURSE PLEASE PROVIDE DETAILS</h4>';
    echo '</div>';

    echo '<div class="row">';
    text_field("pw-language-course-with", "WHO WITH?", "6");
    date_field("pw-language-course-date", "START DATE", "pw-language-course-date", "6");
    echo '</div>';

    echo '<div class="row">';
    text_field("pw-language-course-length", "LENGTH OF COURSE", "6");
    text_field("pw-language-course-title", "COURSE TITLE/LEVEL", "6");
    echo '</div>';   

    echo '<div class="row">';
    select_field(array("ESOL","PRE ENTRY","ENTRY 1","ENTRY 2","ENTRY 3","LEVEL 1","LEVEL 2","COVERSATION GROUP"), "pw-language-course-title", "COURSE TYPE", "6");
    echo '</div>';   

    echo '</div>';
}
/*********************************************************************************/

function immigration_form() {
    echo '<div class="row">';
    text_field("pw_1", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/

function financial_form() {
    echo '<div class="row">';
    text_field("pw_2", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/

function work_form() {
    echo '<div class="row">';
    text_field("pw_3", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/

function work_experience_form() {
    echo '<div class="row">';
    text_field("pw_4", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/
function health_form() {
    echo '<div class="row">';
    text_field("pw_5", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/
function additional_form() {
    echo '<div class="row">';
    text_field("pw_6", "TEST", "6");
    echo '</div>';
}
/*********************************************************************************/

function belong_list_events_for_user() {
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
function belong_list_modules_for_user() {
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
function belong_list_clients() {
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
function belong_send_SMS($message, $numbers) {
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
function belong_get_current_user() {
    $current_user = wp_get_current_user();
    if (!($current_user instanceof WP_User))
        return;
    return $current_user;
}

/***********************************************
Get user object by method specified
************************************************/
function belong_get_user_by($field, $value) {
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
function belong_get_users_role($user_id) {
    $user = new WP_User($user_id);
    if (!empty($user->roles) && is_array($user->roles)) {
        return $user->roles[0];
    }
}

/***********************************************
Return a list of staff members
************************************************/
function get_staff_list() {
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
function datepicker($date_id) {
    ?>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery("#" + <?php echo json_encode($date_id); ?>).datepicker({
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
function staff_select_field($name, $title, $col) {
    $staff_list = get_staff_list();
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "'><option selected='selected'>choose</option>";
    foreach ($staff_list as $item) {
        echo "<option value=" . strtolower($item[0]) . ">" . $item[0] . "</option>";
    }
    echo "</select>";
    echo '</div>';
}

/***********************************************
Dynamically populate select element from array
************************************************/
function select_field($array, $name, $title, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "'><option selected='selected'>choose</option>";
    foreach ($array as $item) {
        echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
    }
    echo "</select>";
    echo '</div>';
}


/***********************************************
Multi select element field from array
************************************************/
function select_multiple($array, $name, $title, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "' multiple='multiple'><option selected='selected'>choose</option>";
    foreach ($array as $item) {
        echo "<option value=" . strtolower($item) . ">" . $item . "</option>";
    }
    echo "</select>";
    echo '</div>';
}

/***********************************************
Standard text field
************************************************/
function text_field($name, $title, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label class="control-label">' . $title . '</label>';
    echo '<input class="form-control" type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</div>';
}

/***********************************************
Inline checkbox
************************************************/
function checkbox_field($name, $title, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label class="checkbox">';
    echo '<input class="form-control" type="checkbox" name="' . $name . '"/>' . $title;
    echo '</label>';
    echo '</div>';
}
/***********************************************
User text field
************************************************/
function user_field($name, $title, $field_name, $id, $col) {
    $user  = belong_get_user_by("ID", $id);
    $field = $user->$field_name;
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($field) ? esc_attr($field) : '') . '" size="50" />';
    echo '</div>';
}


/***********************************************
jquery date picker field
************************************************/
function date_field($name, $title, $date_id, $col) {
    datepicker($date_id);
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input  class="form-control" id="' . $date_id . '" name="' . $name . '" />';
    echo '</div>';
}

/***********************************************
email field with validation
************************************************/
function email_field($name, $title, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="email" name="' . $name . '" value="' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '" size="40" />';
    echo '</div>';
}

/***********************************************
textarea field
************************************************/
function textarea_field($name, $title, $rows, $columns, $col) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<textarea class="form-control" rows="' . $rows . '" cols="' . $columns . '" name="' . $name . '">' . (isset($_POST[$name]) ? esc_attr($_POST[$name]) : '') . '</textarea>';
    echo '</div>';
}


/*****************************************************
child input fields
******************************************************/
function children() {
    echo '<div class="col-md-12">';
    echo '<div class="panel panel-primary">';
    echo '<div class="panel-heading">CHILDREN</div>';
    echo '<div class="panel-body">';
    
    echo '<div id="child_fields">';
    echo '</div>';
    
    echo '<div class="col-md-3 nopadding">';
    echo '<div class="form-group">';
    echo '<input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="" placeholder="CHILD NAME">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-3 nopadding">';
    datepicker("pw-child-dob0");
    echo '<div class="form-group">';
    echo '<input type="text" class="form-control" id="pw-child-dob0" name="pw-child-dob[]" value="" placeholder="DOB">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-3 nopadding">';
    echo '<div class="form-group">';
    echo '<input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="" placeholder="CLIENT NUMBER">';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="col-md-3 nopadding">';
    echo '<div class="form-group">';
    echo '<div class="input-group">';
    echo '<select class="form-control" id="pw-child-uk" name="pw-child-uk[]">';
    echo '<option value="">UK?</option>';
    echo '<option value="yes">YES</option>';
    echo '<option value="no">NO</option>';
    echo '</select>';
    echo '<div class="input-group-btn">';
    echo '<button class="btn btn-success btn-sm" type="button"  onclick="child_fields();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="clear"></div>';
    echo '</div>';
    
    echo '<div class="panel-footer"><small>Press <span class="glyphicon glyphicon-plus gs"></span> to add a child</small>, <small>Press <span class="glyphicon glyphicon-minus gs"></span> to remove a child</small></div>';
    echo '</div>';
    echo '</div>';
    
    ?>
    <script type="text/javascript">
      var child = 0;

      function child_fields() {
        child++;
        var objTo = document.getElementById('child_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + child);
        var rdiv = 'removeclass' + child;
        divtest.innerHTML = '<div class="col-md-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="" placeholder="CHILD NAME"></div></div><div class="col-md-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="pw-child-dob' + child + '" name="pw-child-dob[]" value="" placeholder="DOB"></div></div><div class="col-md-3 nopadding"><div class="form-group"><input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="" placeholder="CLIENT NUMBER"></div></div><div class="col-md-3 nopadding"><div class="form-group"><div class="input-group"><select class="form-control" id="pw-child-uk" name="pw-child-uk[]"><option value="">UK?</option><option value="yes">YES</option><option value="no">NO</option></select><div class="input-group-btn"><button class="btn btn-danger btn-sm" type="button" onclick="remove_child_fields(' + child + ');"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div></div><div class="clear"></div>';
        objTo.appendChild(divtest);
        dob_picker("pw-child-dob" + child);
      }

      function remove_child_fields(rid) {
        jQuery('.removeclass' + rid).remove();
      }

      function dob_picker(dob_id) {
        jQuery(document).ready(function() {
          jQuery("#" + dob_id).datepicker({
            changeYear: true,
            yearRange: "-100:+0",
            dateFormat: 'dd-mm-yy'
          });
        });
      }
    </script>
    <?php
}

?>