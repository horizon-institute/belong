<?php

/***********************************************************
* Scheduled job for Belong notifications
* This will execute daily and send EMAIL/SMS notifications to clients
**********************************************************/

function belong_get_assignments() {
    ob_start();
    echo "Currently reminders triggered each time this page is refreshed<br />";
    $assignment_args = array(
    'posts_per_page' => -1,
    'post_type' => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        foreach ($assignment_posts as $post) {    
            $mobile_numbers = [];
            $current_date = new DateTime();
            $assignment_type   = get_field('assignment_type', $post->ID);
            $assignment_reminder = get_field('assignment_reminder', $post->ID);
            $assignment_reminder_period = get_field('assignment_reminder_period', $post->ID);
            $assignment_reminder_type = get_field('assignment_reminder_type', $post->ID);
            $assignment_users = get_field('assignment_client', $post->ID); //multi select array of clients

            if ($assignment_type == "Modules") {
                $complete_by = get_field('assignment_complete_by', $post->ID);
                $complete_date = new DateTime($complete_by);
                sendReminders($complete_date, $assignment_reminder_period, $assignment_users, $assignment_reminder_type);
            }

            if ($assignment_type == "Events") {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $event_date       = new DateTime($event_datetime);
                sendReminders($event_date, $assignment_reminder_period, $assignment_users, $assignment_reminder_type);
            }
        }
    }
    return ob_get_clean();
}
add_shortcode('belong_assignments', 'belong_get_assignments');


function sendReminders($date, $reminder_period, $users, $reminder_type) {
    if (isReminderTriggered($date, $reminder_period)) {
        $email_addresses = getEmailAddresses($users);
        if ($reminder_type == "email") {
            var_dump($email_addresses);
            // belong_send_emails("Pathways test message body.", "Reminder", $addresses);
        } else if ($reminder_type == "sms") {
            //belong_send_SMS($message, $numbers);
        } else if ($reminder_type == "both") {
            // belong_send_emails("Pathways test message body.", "Reminder", $addresses);
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
function getEmailAddresses($user) {
    $email_addresses = [];
    foreach ($users as $user) {
        var_dump ($user);
        //$email_addresses[] = $user['user_email'];
    }
    return $email_addresses;
}


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
function belong_send_emails($message, $subject, $addresses) {
    foreach ($addresses as $address) {
        wp_mail($address, $subject, $message);
    }
}

?>