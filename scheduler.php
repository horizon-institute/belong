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
        foreach ($assignment_posts as $post) {
            //var_dump($post);
            echo "Reminder: " . get_field('assignment_reminder', $post->ID) . "<br />" ;
            echo "Complete by: " . get_field('assignment_complete_by', $post->ID) . "<br />";
            echo "Reminder Period: " . get_field('assignment_reminder_period', $post->ID) . "<br />";
            echo "Reminder Type: " . get_field('assignment_reminder_type', $post->ID) . "<br />";
            echo "*******************************************************************" . "<br />";

        }
        
    }

    //send a test email
    $addresses = array("javidyousaf@outlook.com");
    belong_send_EMAIL("Pathways test message body.", "Email from Pathways system", $addresses);

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