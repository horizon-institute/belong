<?php

/***********************************************************
* Scheduled job for Belong notifications
* This will execute daily and send EMAIL/SMS notifications to clients
**********************************************************/

function belong_get_assignments() {
    ob_start();
    $assignment_args = array(
    'posts_per_page' => -1,
    'post_type' => 'assignments'
    );
    
    $assignment_posts = get_posts($assignment_args);
    if ($assignment_posts) {
        $current_date = new DateTime();
        foreach ($assignment_posts as $post) {

            $assignment_type   = get_field('assignment_type', $post->ID);
            $assignment_reminder = get_field('assignment_reminder', $post->ID);
            $assignment_reminder_period = get_field('assignment_reminder_period', $post->ID);
            $assignment_reminder_type = get_field('assignment_reminder_type', $post->ID);

            echo "Reminder: " . $assignment_reminder . "<br />" ;
            echo "Reminder Period: " . $assignment_reminder_period . "<br />";
            echo "Reminder Type: " . $assignment_reminder_type . "<br />";
            echo "Current date/time: " . $current_date . "<br />" ;

            if ($assignment_type == "Modules") {
                $complete_by = get_field('assignment_complete_by', $post->ID);
                echo "Complete module by: " . $complete_by  . "<br />";
            }

            if ($assignment_type == "Events") {
                //get event object and ge the date/time field
                $assignment_event = get_field('assignment_select_event', $post->ID);
                $event_datetime   = get_field('event_date', $assignment_event->ID);
                $event_date         = new DateTime($event_datetime);
                echo "Event date/time: " . $event_date . "<br />";
            }

            echo "*******************************************************************" . "<br />";

        }
        
    }

    //send a test email
    // $addresses = array("javidyousaf@outlook.com");
    // belong_send_EMAIL("Pathways test message body.", "Email from Pathways system", $addresses);

    return ob_get_clean();
}

add_shortcode('belong_assignments', 'belong_get_assignments');

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