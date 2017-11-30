<?php

/***********************************************************
* Scheduled job for Belong notifications
* This will execute daily and send EMAIL/SMS notifications to clients
**********************************************************/

function belong_get_assignments() {
    ob_start();

    echo "Reminders triggered each time this page ois refreshed";

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
            $assignment_clients = get_field('assignment_client', $post->ID); //multi select array of clients

            if ($assignment_type == "Modules") {
                $complete_by = get_field('assignment_complete_by', $post->ID);
                $complete_date = new DateTime($complete_by);
                if (isReminderTriggered($complete_date, $assignment_reminder_period)) {
                    if ($assignment_reminder_type == "email") {
                        $email_addresses = getEmailAddresses($assignment_clients);
                        var_dump($email_addresses);
                    } else if ($assignment_reminder_type == "sms") {

                    } else if ($assignment_reminder_type == "both") {

                    }
                }
            }

            if ($assignment_type == "Events") {
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $event_date       = new DateTime($event_datetime);
                if (isReminderTriggered($event_date, $assignment_reminder_period)) {
                    if ($assignment_reminder_type == "email") {
                        $email_addresses = getEmailAddresses($assignment_clients);
                        var_dump($email_addresses);
                    } else if ($assignment_reminder_type == "sms") {
                            
                    } else if ($assignment_reminder_type == "both") {
                        
                    }                   
                }
            }
        }

    }

    //send a test email
    // $addresses = array("javidyousaf@outlook.com");
    // belong_send_EMAIL("Pathways test message body.", "Email from Pathways system", $addresses);

    return ob_get_clean();
}
add_shortcode('belong_assignments', 'belong_get_assignments');


/***********************************************************
* check reminder period
* $ReminderPeriod - is in days
* $scheduledDate - the Module/Event DateTime object
***********************************************************/
function isReminderTriggered($scheduledDate, $reminderPeriod) {
    $now = new DateTime();
    return ($now < $scheduledDate && $now > $scheduledDate->sub(new DateInterval('P' . $reminderPeriod . 'D'))); 
}




/***********************************************************
* Return an array of email addresses from clients object array
***********************************************************/
function getEmailAddresses($clients) {
    $email_addresses = [];
    foreach ($clients as $client) {
        $email_addresses[] = $client['user_email'];
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
function belong_send_EMAIL($message, $subject, $addresses) {
    foreach ($addresses as $address) {
        wp_mail($address, $subject, $message);
    }
}

?>