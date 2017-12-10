<?php

/***********************************************************
* Scheduled job for Belong notifications
* This will execute daily and send EMAIL/SMS notifications to clients
**********************************************************/

function belong_send_notifications() {
    ob_start();
    echo "REMINDERS<br /><br />";
    $assignment_args = array(
    'posts_per_page' => -1,
    'post_type' => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        foreach ($assignment_posts as $post) {  
            $email_addresses = array();
            $current_date = new DateTime();
            $assignment_type   = get_field('assignment_type', $post->ID);
            $assignment_reminder = get_field('assignment_reminder', $post->ID);
            $assignment_reminder_period = get_field('assignment_reminder_period', $post->ID);
            $assignment_reminder_type = get_field('assignment_reminder_type', $post->ID);
            $assignment_email_field = get_field ('assignment_client', $post->ID); // get multi select object         

            if ($assignment_type == "Modules") {
                $complete_by = get_field('assignment_complete_by', $post->ID);
                $complete_date = new DateTime($complete_by);
                sendReminders($complete_date, $assignment_reminder_period, $assignment_email_field, $assignment_reminder_type);
            }

            if ($assignment_type == "Events") {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $event_date       = new DateTime($event_datetime);
                sendReminders($event_date, $assignment_reminder_period, $assignment_email_field, $assignment_reminder_type);
            }
        }
    }
    return ob_get_clean();
}
add_shortcode('belong_notifications', 'belong_send_notifications');

function sendReminders($date, $reminder_period, $emails, $reminder_type) {
    if (isReminderTriggered($date, $reminder_period)) {
        if ($reminder_type == "email") {
           belong_send_emails("Pathways test message body.", "Reminder", $emails);
        } else if ($reminder_type == "sms") {
            // belong_send_SMS($message, $numbers);
        } else if ($reminder_type == "both") {
            belong_send_emails("Pathways test message body.", "Reminder", $emails);
            // belong_send_SMS($message, $numbers);
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
// function getEmailAddresses($field, $values) {
//         $email_array = array();
//         foreach ($values as $value) {
//             $email_array[] = $field['choices'][$value];
//         }
//         // output for test
//         foreach ($email_array as $email) {
//             echo $email . "<br />";
//         }
//    return $email_array;
// }


/***********************************************************
* Send SMS to users
* $numbers - is an array of mobile numbers. 
* $message - message to send.
***********************************************************/
function belong_send_SMS($message, $numbers) {
    global $sms;
    $sms->to  = array(
    $numbers
    );
    $sms->msg = $message;
    $sms->SendSMS();
}

/***********************************************************
* Send EMAIL to users
* $addresses - is an array of email addresses. 
* $message - message to send.
***********************************************************/
function belong_send_emails($message, $subject, $emails) {

    if (is_array($emails) || is_object($emails)) {
        foreach ($emails as $users) {
            //wp_mail($address, $subject, $message);
            echo "sending email to: " . $users['user_email'] . " | Subject: " . $subject  . " | Message: " .$message . "<br />";
        }
    } else {
        echo "not an email array!";
    }
}

?>