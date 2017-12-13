<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
/*********************************************************
* Scheduled job for Belong notifications
* This will execute daily and send EMAIL/SMS notifications
**********************************************************/

$assignment_args = array(
'posts_per_page' => -1,
'post_type' => 'assignments'
);

$assignment_posts = get_posts($assignment_args);
if ($assignment_posts) {
    foreach ($assignment_posts as $post) {  
        $current_date = new DateTime();
        $assignment_type   = get_field('assignment_type', $post->ID);
        $assignment_title = $post->post_title;
        $assignment_reminder = get_field('assignment_reminder', $post->ID);
        $assignment_reminder_period = get_field('assignment_reminder_period', $post->ID);
        $assignment_reminder_type = get_field('assignment_reminder_type', $post->ID);
        $assignment_client_field = get_field('assignment_client', $post->ID);
        $emails = getEmailAddresses($assignment_client_field);
        $mobiles = getMobileNumbers($assignment_client_field);
        
        if (isset($emails)) {
            if ($assignment_type == "Modules") {
                $complete_by = get_field('assignment_complete_by', $post->ID);
                $complete_date = new DateTime($complete_by);
                sendReminders($complete_date, $assignment_reminder_period, $emails, $mobiles, $assignment_reminder_type, $assignment_title);
            }

            if ($assignment_type == "Events") {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $event_date       = new DateTime($event_datetime);
                sendReminders($event_date, $assignment_reminder_period, $emails, $mobiles, $assignment_reminder_type, $assignment_title);
            }
        }
    }
}

function sendReminders($date, $reminder_period, $emails, $mobiles, $reminder_type, $title) {
    $trigger = isReminderTriggered($date, $reminder_period);
    $message = "REMINDER. The following action: " . $title . " has been assigned to you to complete by " . $date->format('j M Y');
    if (isset($trigger) && $trigger == 1) {
        if ($reminder_type == "email") {
           belong_send_emails($message, "REMINDER FROM PATHWAYS", $emails);
        } else if ($reminder_type == "sms") {
            belong_send_SMS($message, $mobiles);
        } else if ($reminder_type == "both") {
            belong_send_emails($message, "REMINDER FROM PATHWAYS", $emails);
            belong_send_SMS($message, $mobiles);
        }                   
    }
}

/***********************************************************
* Are we within the reminder period?
* $ReminderPeriod - is in days
* $scheduledDate - the Module/Event DateTime object
***********************************************************/
function isReminderTriggered($scheduledDate, $reminderPeriod) {
    $now = new DateTime();
    return ($now < $scheduledDate && $now > $scheduledDate->sub(new DateInterval('P' . $reminderPeriod . 'D')));
}

/***********************************************************
* Return an array of email addresses from user object array
***********************************************************/
function getEmailAddresses($client_object) {
    if (is_array($client_object) || is_object($client_object)) {
        $email_array = array();
        foreach($client_object as $client) {
            array_push($email_array, $client["user_email"]);
        }
        return $email_array;
    } else {
        return null;
    }
}

/***********************************************************
* Return an array of mobile numbers
***********************************************************/
function getMobileNumbers($client_object) {
    if (is_array($client_object) || is_object($client_object)) {
        $post_id = 337;
        $mobiles = array();
        foreach($client_object as $client) {
            $mobile_number = "";
            $id = $client["ID"];
            $client_profile = get_post_meta( $post_id, "client_profile_" . $id, true );
            $mobile_number = $client_profile["pw-client-telephone"];
            if (!empty($mobile_number)) {
                array_push($mobiles, str_replace(' ','',$mobile_number));
            }         
        }
        return $mobiles;
    } else {
        return null;
    }
}

/***********************************************************
* Send SMS to users
* $numbers - is an array of mobile numbers. 
* $message - message to send.
***********************************************************/
function belong_send_SMS($message, $numbers) {
    global $sms;
    $sms->to  = $numbers;
    $sms->msg = $message;
    $sms->SendSMS();
}

/***********************************************************
* Send EMAIL to users
* $addresses - is an array of email addresses. 
* $message - message to send.
***********************************************************/
function belong_send_emails($message, $subject, $emails) {
    foreach($emails as $email) {
        wp_mail($email, $subject, $message);
    }
}

?>