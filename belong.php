<?php

/**
* Plugin Name: Belong
* Plugin URI: http://belong-horizon.cloudapp.net
* Bitbucket Plugin URI: https://javidyousaf@bitbucket.org/javidyousaf/belong.git
* Description: Custom functionality for Belong Nottingham CRM
* Version: 0.3.4.2
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
    $post_id = get_the_ID();
    $cm = get_post_meta($post_id, "client_profile_" . $id)[0];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST)) {
            /** Add or update client profile in meta table */
            if (! add_post_meta($post_id, "client_profile_" . $id, $_POST, true)) {
                update_post_meta($post_id, "client_profile_" . $id, $_POST);
            }
            /** get selected clients profile from meta table */
            $cm = get_post_meta($post_id, "client_profile_" . $id)[0];
        }
    }
    
    echo '<form action="" method="post" id="tab">';
    echo '<div class="form-group">';
    client_registration($id, $cm);
    echo '</div>';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="form-group">';
    echo '<button type="submit" class="btn btn-default" name="submit">UPDATE</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    return ob_get_clean();
}

add_shortcode('client_registration', 'client_registration_form');
/*********************************************************************************/

function client_view_form() {
    ob_start();
    $current_user = belong_get_current_user();
    $id           = $current_user->ID;
    $post_id = 337; //post_id for client profile post
    $cm = get_post_meta($post_id, "client_profile_" . $id)[0];
    
    echo '<form action="" method="post" id="tab">';
    echo '<fieldset disabled>';
    client_registration($id, $cm);
    echo '</fieldset>';
    echo '</form>';
    return ob_get_clean();
}

add_shortcode('client_view', 'client_view_form');
/*********************************************************************************/

function client_registration($id, $cm) {
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
    personal_form($id, $cm);
    echo '</div>';
    echo '<div class="tab-pane fade" id="education">';
    education_form($cm);
    echo '</div>';
    echo '<div class="tab-pane fade" id="language">';
    language_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="immigration">';
    immigration_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="financial">';
    financial_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="work">';
    work_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="work_experience">';
    work_experience_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="health">';
    health_form($cm);
    echo '</div>';
    
    echo '<div class="tab-pane fade" id="additional">';
    additional_form($cm);
    echo '</div>';
    
    echo '</div>';
}
/*********************************************************************************/

function personal_form($id, $cm) {
    echo '<div class="row">';
    echo '<input type="hidden" name="pw-client-id" value="' . $id . '" />';
    date_field("pw-registration-date", "DATE", "pw-registration-date", "4", $cm);
    text_field("pw-source", "SOURCE", "4", $cm);
    text_field("pw-client-number", "CLIENT NUMBER", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    staff_select_field("pw-interviewers-name", "INTERVIEWERS NAME", "6", $cm);
    staff_select_field("pw-case-owner", "CASE OWNER", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    echo '<fieldset disabled>';
    user_field("pw-display-name", "DISPLAY NAME", "display_name", $id, "6", $cm);
    user_field("pw-email", "EMAIL ADDRESS", "user_email", $id, "6", $cm);
    echo '</fieldset>';
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Male","Female"), "pw-client-gender", "GENDER", "6", $cm);
    text_field("pw-client-telephone", "TELEPHONE NUMBER", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-client_dob", "DATE OF BIRTH", "client_dob", "12", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-address", "ADDRESS", "5", "35", "12", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-postcode", "POST CODE", "6", $cm);
    text_field("pw-accomodation-type", "ACCOMODATION TYPE", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-nationality", "NATIONALITY", "4", $cm);
    text_field("pw-nationality-code", "CODE", "4", $cm);
    select_field(array("Single","Married","Civil Partnership","Common Law"), "pw-relationship-status", "RELATIONSHIP STATUS", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-religion", "RELIGION", "6", $cm);
    text_field("pw-placeofworship", "PLACE OF WORSHIP", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-spouse-name", "SPOUSE/PARTNER NAME", "6", $cm);
    text_field("pw-spouse-cn", "SPOUSE/PARTNER CLIENT NUMBER", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-spouse-uk", "UK RESIDENT?", "6", $cm);
    select_field(array("Yes","No"), "pw-spouse-travel", "DID THEY TRAVEL TO THE UK WITH YOU?", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    children($cm);
    echo '</div>';
    
}
/*********************************************************************************/

function education_form($cm) {
    echo '<div class="row">';
    select_multiple(array("Primary School","Secondary School","6th Form College","College","University","Other"), "pw-education-attended", "HAVE YOU EVER ATTENDED ANY OF THE FOLLOWING?", "6", $cm);
    text_field("pw-education-other", "OTHER", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>WHAT FORMAL QUALIFICATIONS DO YOU HAVE AND HAVE THEY BEEN RECOGNISED?</label>';
    echo '</div>';
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-1", "QUALIFICATION", "10", $cm);
    select_field(array("Yes","No"), "pw-education-recognised-1", "RECOGNISED?", "2", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-2", "QUALIFICATION", "10", $cm);
    select_field(array("Yes","No"), "pw-education-recognised-2", "RECOGNISED?", "2", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-3", "QUALIFICATION", "10", $cm);
    select_field(array("Yes","No"), "pw-education-recognised-3", "RECOGNISED?", "2", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-4", "QUALIFICATION", "10", $cm);
    select_field(array("Yes","No"), "pw-education-recognised-4", "RECOGNISED?", "2", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-education-qualification-5", "QUALIFICATION", "10", $cm);
    select_field(array("Yes","No"), "pw-education-recognised-5", "RECOGNISED?", "2", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-education-not-recognised", "IF THEY HAVE NOT BEEN RECOGNISED PLEASE TELL US WHY", "5", "35", "12", $cm);
    echo '</div>';
}
/*********************************************************************************/

function language_form($cm) {
    echo '<div class="row">';
    text_field("pw-language-primary", "WHAT IS YOUR PRIMARY LANGUAGE?", "8", $cm);
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-1", "ABILITY", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>DO YOU SPEAK ANY OTHER LANGUAGES?</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="row">';
    text_field("pw-language-2", "LANGUAGE", "8", $cm);
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-2", "ABILITY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-3", "LANGUAGE", "8", $cm);
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-3", "ABILITY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-4", "LANGUAGE", "8", $cm);
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-4", "ABILITY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-language-5", "LANGUAGE", "8", $cm);
    select_field(array("SPOKEN","WRITTEN"), "pw-language-spoken-5", "ABILITY", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-education-translate", "ARE YOU PREPARED TO ASSIST IN TRANSLATION?", "6", $cm);
    select_field(array("Yes","No"), "pw-language-assistance", "DO YOU NEED ASSISTANCE WITH ENGLISH?", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>IF YOU ARE ALREADY ON A COURSE PLEASE PROVIDE DETAILS</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="row">';
    text_field("pw-language-course-with", "WHO WITH?", "6", $cm);
    date_field("pw-language-course-date", "START DATE", "pw-language-course-date", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    text_field("pw-language-course-length", "LENGTH OF COURSE", "6, $cm");
    text_field("pw-language-course-title", "COURSE TITLE/LEVEL", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("ESOL","PRE ENTRY","ENTRY 1","ENTRY 2","ENTRY 3","LEVEL 1","LEVEL 2","COVERSATION GROUP"), "pw-language-course-title", "COURSE TYPE", "6", $cm);
    echo '</div>';
}
/*********************************************************************************/

function immigration_form($cm) {
    echo '<div class="row">';
    select_field(array("ASYLUM SEEKER","REFUGEE","BRITISH","EU CITIZEN","ECONOMIC MIGRANT","OTHER"), "pw-immigration-status", "IMMIGRATION STATUS", "6", $cm);
    text_field("pw-immigration-status-other", "OTHER", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    date_field("pw-immigration-arrival-date", "WHAT DATE DID YOU ARRIVE IN THE UK?", "pw-immigration-arrival-date", "6", $cm);
    select_field(array("Yes","No"), "pw-immigration-travel-directly", "DID YOU TRAVEL DIRECTLY FROM YOUR COUNTRY?", "6", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>IF NO WHAT COUNTRIES DID YOU VISIT AND HOW LONG DID YOU STAY?</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="row">';
    text_field("pw-immigration-country-1", "COUNTRY", "6", $cm);
    text_field("pw-immigration-country-stay-1", "MONTHS", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-immigration-country-2", "COUNTRY", "6", $cm);
    text_field("pw-immigration-country-stay-2", "MONTHS", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-immigration-country-3", "COUNTRY", "6", $cm);
    text_field("pw-immigration-country-stay-3", "MONTHS", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-immigration-country-4", "COUNTRY", "6", $cm);
    text_field("pw-immigration-country-stay-4", "MONTHS", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-immigration-why", "WHY DID YOU CHOOSE TO SETTLE IN THE UK?", "5", "35", "12", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-immigration-assistance", "DO YOU NEED ASSISTANCE APPLYING FOR UK CITIZENSHIP?", "6", $cm);
    select_field(array("Yes","No"), "pw-immigration-assistance-help", "IS SOMEONE ALREADY ASSISTING YOU WITH YOUR APLICATION?", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-immigration-organisation-name", "NAME OF ORGANISATION", "6", $cm);
    text_field("pw-immigration-organisation-solicitor", "SOLICITOR", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-immigration-organisation-stage", "AT WHAT STAGE IS YOUR APPLICATION?", "6", $cm);
    date_field("pw-immigration-organisation-appointment", "WHEN IS YOUR NEXT APPOINTMENT?", "pw-immigration-organisation-appointment", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-immigration-organisation-purpose", "WHAT IS THE PURPOSE OF THE APPOINTMENT?", "5", "35", "12", $cm);
    echo '</div>';
}
/*********************************************************************************/

function financial_form($cm) {
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-financial-bank", "DO YOU HAVE A UK BANK ACCOUNT?", "12", $cm);
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-financial-benefits-claaming", "ARE YOU CURRENTLY CLAIMING BENEFITS FROM THE UK GOVERNMENT?", "12", $cm);
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-financial-benefits-assessment", "HAVE YOU HAD AN INDEPENDENT ASSESSMENT OF THE BENEFITS YOU MAY BE ELIGIBLE FOR?", "12", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>TELL US WHAT BENTFITS YOU ARE CURRENTLY IN RECIEPT OF?</label>';
    echo '</div>';
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-financial-benefit-1", "BENEFIT", "4", $cm);
    text_field("pw-financial-benefit-amount-1", "AMOUNT", "4", $cm);
    select_field(array("WEEKLY","MONTHLY","FORTNIGHTLY"), "pw-financial-benefit-frequency-1", "FREQUENCY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-financial-benefit-2", "BENEFIT", "4", $cm);
    text_field("pw-financial-benefit-amount-2", "AMOUNT", "4", $cm);
    select_field(array("WEEKLY","MONTHLY","FORTNIGHTLY"), "pw-financial-benefit-frequency-2", "FREQUENCY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-financial-benefit-3", "BENEFIT", "4", $cm);
    text_field("pw-financial-benefit-amount-3", "AMOUNT", "4", $cm);
    select_field(array("WEEKLY","MONTHLY","FORTNIGHTLY"), "pw-financial-benefit-frequency-3", "FREQUENCY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-financial-benefit-4", "BENEFIT", "4", $cm);
    text_field("pw-financial-benefit-amount-4", "AMOUNT", "4", $cm);
    select_field(array("WEEKLY","MONTHLY","FORTNIGHTLY"), "pw-financial-benefit-frequency-4", "FREQUENCY", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    text_field("pw-financial-benefit-5", "BENEFIT", "4", $cm);
    text_field("pw-financial-benefit-amount-5", "AMOUNT", "4", $cm);
    select_field(array("WEEKLY","MONTHLY","FORTNIGHTLY"), "pw-financial-benefit-frequency-5", "FREQUENCY", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_multiple(array("RENT","COUNCIL TAX","GAS","ELECTRICITY","WATER RATES","TV LICENCE","HEALTH CARE COSTS","SCHOOL TEAMS","CREDIT CARDS","TRANSPORT","TELEPHONE","MOBILE PHONES"), "pw-financial-understand", "DO YOU UNDERSTND THE FOLLOWING?", "6", $cm);
    echo '</div>';
    
    
}
/*********************************************************************************/

function work_form($cm) {
    echo '<div class="row">';
    text_field("pw-work-ni-number", "NATIONAL INSURANCE NUMBER", "4", $cm);
    select_field(array("NOT YET ELIGIBLE","NOT APPLIED"), "pw-work-ni-none", "NO NATIONAL INSURANCE NUMBER - WHY?", "4", $cm);
    select_field(array("Yes","No"), "pw-work-registered", "HAVE YOU REGISTERED AS UNEMPLOYED?", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-work-cv", "DO YOU HAVE A CV?", "4", $cm);
    select_field(array("Yes","No"), "pw-work-assistance", "DO YOU NEED ASSISTANCE IN SEARCHING FOR WORK?", "4", $cm);
    select_field(array("Yes","No"), "pw-work-computer", "ARE YOU ABLE TO USE A COMPUTER?", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("0-6 MONTHS","7-12 MONTHS","13-24 MONTHS","24 MONTHS +"), "pw-work-how-long", "HOW LONG HAVE YOU BEEN LOOKING FOR WORK?", "4", $cm);
    textarea_field("pw-work-barriers", "ARE THERE ANY BARRIERS STOPPING YOU FROM WORKING? PLEASE LIST THEM.", "5", "35", "8", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>DO YOU NEED HELP WITH ANY OF THE FOLLOWING?</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-work-culture", "UNDERSTANDING WORK CULTURE", "4", $cm);
    select_field(array("Yes","No"), "pw-work-interview", "INTERVIEW TECHNIQUES", "4", $cm);
    select_field(array("Yes","No"), "pw-work-experience", "WORK EXPERIENCE", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-work-assistance", "ARE YOU RECEIVING ASSISTANCE TO FIND WORK ELSEWHERE?", "4", $cm);
    textarea_field("pw-work-assistance-whom", "IF YES FROM WHOM?","5", "35", "8", $cm);
    echo '</div>';
}

/*********************************************************************************/

function work_experience_form($cm) {
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<label>PLEASE TELL US ABOUT YOUR WORK EXPERIENCE?</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="row">';
    date_field("pw-experience-from-1", "FROM", "pw-experience-from-1", "2", $cm);
    date_field("pw-experience-to-1", "TO", "pw-experience-to-1", "2", $cm);
    text_field("pw-experience-role-1", "ROLE", "4", $cm);
    text_field("pw-experience-role-where-1", "WHERE", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-experience-from-2", "FROM", "pw-experience-from-2", "2", $cm);
    date_field("pw-experience-to-2", "TO", "pw-experience-to-2", "2", $cm);
    text_field("pw-experience-role-2", "ROLE", "4", $cm);
    text_field("pw-experience-role-where-2", "WHERE", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-experience-from-3", "FROM", "pw-experience-from-3", "2", $cm);
    date_field("pw-experience-to-3", "TO", "pw-experience-to-3", "2", $cm);
    text_field("pw-experience-role-3", "ROLE", "4", $cm);
    text_field("pw-experience-role-where-3", "WHERE", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-experience-from-4", "FROM", "pw-experience-from-4", "2", $cm);
    date_field("pw-experience-to-4", "TO", "pw-experience-to-4", "2", $cm);
    text_field("pw-experience-role-4", "ROLE", "4", $cm);
    text_field("pw-experience-role-where-4", "WHERE", "4", $cm);
    echo '</div>';
    echo '<div class="row">';
    date_field("pw-experience-from-5", "FROM", "pw-experience-from-5", "2", $cm);
    date_field("pw-experience-to-5", "TO", "pw-experience-to-5", "2", $cm);
    text_field("pw-experience-role-5", "ROLE", "4");
    text_field("pw-experience-role-where-5", "WHERE", "4", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-experience-vocational", "DO YOU HAVE ANY VOCATIONAL QUALIFICATIONS?", "4", $cm);
    textarea_field("pw-experience-vocational-description", "DESCRIPTION","5", "35", "8", $cm);
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-experience-recognised", "HAS YOUR TRAINING BEEN RECOGNISED?", "4", $cm);
    textarea_field("pw-experience-recognised-description", "DESCRIPTION","5", "35", "8", $cm);
    echo '</div>';
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-experience-volunteering", "DO YOU HAVE ANY EXPERIENCE VOLUNTEERING?", "4");
    textarea_field("pw-experience-volunteering-description", "DESCRIPTION","5", "35", "8", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-experience-aspirations", "WHAT ARE YOUR FUTURE ASPIRATIONS?","5", "35", "12", $cm);
    echo '</div>';
    
    
}
/*********************************************************************************/
function health_form($cm) {
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-health-doctor", "ARE YOU REGISTERED WITH A DOCTOR?", "4", $cm);
    textarea_field("pw-health-doctor-address", "DOCTORS NAME & ADDRESS","5", "35", "8", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    textarea_field("pw-health-issues", "DO YOU HAVE ANY CURRENT MEDICAL ISSUES?","5", "35", "12", $cm);
    echo '</div>';
    
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-health-dentist", "ARE YOU REGISTERED WITH A DENTIST?", "4", $cm);
    textarea_field("pw-health-dentist-address", "DENTISTS NAME & ADDRESS","5", "35", "8", $cm);
    echo '</div>';
}
/*********************************************************************************/
function additional_form($cm) {
    echo '<div class="row">';
    select_field(array("Yes","No"), "pw-additional-assistance", "IS THERE ANY ONE ELSE ASSISTING YOU?", "6", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-additional-assistance-description", "DESCRIPTION","5", "35", "12", $cm);
    echo '</div>';
    echo '<div class="row">';
    textarea_field("pw-additional-notes", "ADDITIONAL NOTES","20", "35", "12", $cm);
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
function staff_select_field($name, $title, $col, $cm) {
    $staff_list = get_staff_list();
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "'><option>choose</option>";
    foreach ($staff_list as $item) {
        echo "<option value=" . strtolower(str_replace(' ', '_',$item[0])) . " ";
        if($cm[$name] == strtolower(str_replace(' ', '_',$item[0]))) {
            echo "selected='selected'";
        }
        echo ">" . str_replace('_', ' ',$item[0] );
        echo "</option>";
    }
    echo "</select>";
    echo '</div>';
}

/***********************************************
Dynamically populate select element from array
************************************************/
function select_field($array, $name, $title, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "'><option>choose</option>";
    foreach ($array as $item) {
        echo "<option value=" . strtolower(str_replace(' ', '_',$item)) . " "; 
        if($cm[$name] == strtolower(str_replace(' ', '_',$item))) {
            echo "selected='selected'";
        }
        echo ">" . str_replace('_', ' ',$item);
        echo "</option>";
    }
    echo "</select>";
    echo '</div>';
}


/***********************************************
Multi select element field from array
************************************************/
function select_multiple($array, $name, $title, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo "<select class='form-control' name='" . $name . "[]' multiple='multiple'><option>choose</option>";
    foreach ($array as $item) {
        echo "<option value=" . strtolower(str_replace(' ', '_',$item)) . " ";
        foreach($cm[$name] as $selected) {
            if($selected == strtolower(str_replace(' ', '_',$item))) {
                echo "selected='selected'";
            }
        }
        echo ">" . str_replace('_', ' ',$item);
        echo "</option>";
    }
    echo "</select>";
    echo '</div>';
}

/***********************************************
Standard text field
************************************************/
function text_field($name, $title, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label class="control-label">' . $title . '</label>';
    echo '<input class="form-control" type="text" name="' . $name . '" pattern="[a-zA-Z0-9 ]+" value="' . (isset($cm[$name]) ? esc_attr($cm[$name]) : '') . '" size="40" />';
    echo '</div>';
}

/***********************************************
Checkbox
************************************************/
function checkbox_field($name, $title, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label class="checkbox">';
    echo '<input class="form-control" type="checkbox" name="' . $name . '"/>' . $title;
    echo '</label>';
    echo '</div>';
}
/***********************************************
User text field
************************************************/
function user_field($name, $title, $field_name, $id, $col, $cm) {
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
function date_field($name, $title, $date_id, $col, $cm) {
    datepicker($date_id);
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input  class="form-control" id="' . $date_id . '" name="' . $name . '" value="' . (isset($cm[$name]) ? esc_attr($cm[$name]) : '') . '" />';
    echo '</div>';
}

/***********************************************
email field with validation
************************************************/
function email_field($name, $title, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<input class="form-control" type="email" name="' . $name . '" value="' . (isset($cm[$name]) ? esc_attr($cm[$name]) : '') . '" size="40" />';
    echo '</div>';
}

/***********************************************
textarea field
************************************************/
function textarea_field($name, $title, $rows, $columns, $col, $cm) {
    echo '<div class="col-md-' . $col . '" >';
    echo '<label>' . $title . '</label>';
    echo '<textarea class="form-control" rows="' . $rows . '" cols="' . $columns . '" name="' . $name . '">' . (isset($cm[$name]) ? esc_attr($cm[$name]) : '') . '</textarea>';
    echo '</div>';
}


/*****************************************************
Dynamic child input fields
******************************************************/
function children($cm) {
    $child_names = $cm["pw-child-name"];
    $child_dobs = $cm["pw-child-dob"];
    $child_cns = $cm["pw-child-cn"];
    $child_uks = $cm["pw-child-uk"];

    echo '<div class="col-md-12">';
    echo '<label>CHILDREN</label>';
    echo '<div id="child_fields">';
    echo '</div>';
    

    if (isset($child_names)) {
        for($index = 0; $index < count($child_names) - 1; $index++) {

            if($index == 0) { //manually generate first line
                echo '<div class="col-md-3">';
                echo '<div class="form-group">';
                echo '<input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="' . (isset($child_names[$index]) ? esc_attr($child_names[$index]) : '') . '" placeholder="CHILD NAME">';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="col-md-3">';
                datepicker("pw-child-dob0");
                echo '<div class="form-group">';
                echo '<input type="text" class="form-control" id="pw-child-dob0" name="pw-child-dob[]" value="' . (isset($child_dobs[$index]) ? esc_attr($child_dobs[$index]) : '') . '" placeholder="DOB">';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="col-md-3">';
                echo '<div class="form-group">';
                echo '<input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="' . (isset($child_cns[$index]) ? esc_attr($child_cns[$index]) : '') . '" placeholder="CLIENT NUMBER">';
                echo '</div>';
                echo '</div>';
                
                echo '<div class="col-md-3">';
                echo '<div class="form-group">';
                echo '<div class="input-group">';
                echo '<select class="form-control" id="pw-child-uk" name="pw-child-uk[]">';
                
                $array = ["UK?","Yes","No"];
                foreach ($array as $item) {
                    echo "<option value=" . strtolower(str_replace(' ', '_',$item)) . " ";
                    if($child_uks[$index] == strtolower(str_replace(' ', '_',$item))) {
                        echo "selected='selected'";
                    }
                    echo ">" . str_replace('_', ' ',$item);
                    echo "</option>";
                }
                echo '</select>';
                echo '<div class="input-group-btn">';
                echo '<button class="btn btn-success btn-sm" type="button"  onclick="child_fields(' . $cm . ');"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

            } else {
                //subsequent lines should generate dynamically ???
                ?>
                <script type="text/javascript">
                jQuery(document).ready(function() {
                    child_fields(<?php echo $cm; ?>);
                });
                </script>
                <?php
            }
            echo '<div class="clear"></div>';
            echo '</div>';
        }

    } else {
        echo '<div class="col-md-3">';
        echo '<div class="form-group">';
        echo '<input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="" placeholder="CHILD NAME">';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-md-3">';
        datepicker("pw-child-dob0");
        echo '<div class="form-group">';
        echo '<input type="text" class="form-control" id="pw-child-dob0" name="pw-child-dob[]" value="" placeholder="DOB">';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-md-3">';
        echo '<div class="form-group">';
        echo '<input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="" placeholder="CLIENT NUMBER">';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-md-3">';
        echo '<div class="form-group">';
        echo '<div class="input-group">';
        echo '<select class="form-control" id="pw-child-uk" name="pw-child-uk[]">';
        
        $array = ["UK?","Yes","No"];
        foreach ($array as $item) {
            echo "<option value=" . strtolower(str_replace(' ', '_',$item)) . " ";
            echo ">" . str_replace('_', ' ',$item);
            echo "</option>";
        }
        echo '</select>';

        echo '<div class="input-group-btn">';
        echo '<button class="btn btn-success btn-sm" type="button"  onclick="child_fields(' . $cm . ');"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>';
        echo '</div>';
        echo '<div class="clear"></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

    }
    // echo '</div>';
    // echo '<div class="clear"></div>';
    // echo '</div>';
    // echo '</div>';
    // echo '</div>';


    ?>
      <script type="text/javascript">
        var child = 0;

        function child_fields(cm) {
            //console.log("CM variable: " + cm);
          child++;
          console.log("CM:" + cm, " -- Child no: " + child);
          var objTo = document.getElementById('child_fields')
          var childData = document.createElement("div");
          childData.setAttribute("class", "form-group removeclass" + child);
          var rdiv = 'removeclass' + child;
          var html = [];

        if(cm && cm.hasOwnProperty('pw-child-name')) {
            
            var child_names = cm["pw-child-name"];
            var child_dobs = cm["pw-child-dob"];
            var child_cns = cm["pw-child-cn"];
            var child_uks = cm["pw-child-uk"];
            html.push(
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="' + (child_names[child]) + '" placeholder="CHILD NAME"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-dob' + child + '" name="pw-child-dob[]" value="' + (child_dobs[child])  + '" placeholder="DOB"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="' + (child_cns[child])  + '" placeholder="CLIENT NUMBER"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<div class="input-group"><select class="form-control" id="pw-child-uk" name="pw-child-uk[]">',
            '<option value="">UK?</option><option value="yes">YES</option>',
            '<option value="no">NO</option></select>',
            '<div class="input-group-btn"><button class="btn btn-danger btn-sm" type="button" onclick="remove_child_fields(' + child + ');">',
            '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div></div><div class="clear"></div>'
          );

        } else {
            html.push(
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-name" name="pw-child-name[]" value="" placeholder="CHILD NAME"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-dob' + child + '" name="pw-child-dob[]" value="" placeholder="DOB"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<input type="text" class="form-control" id="pw-child-cn" name="pw-child-cn[]" value="" placeholder="CLIENT NUMBER"></div></div>',
            '<div class="col-md-3 nopadding"><div class="form-group">',
            '<div class="input-group"><select class="form-control" id="pw-child-uk" name="pw-child-uk[]">',
            '<option value="">UK?</option><option value="yes">YES</option>',
            '<option value="no">NO</option></select>',
            '<div class="input-group-btn"><button class="btn btn-danger btn-sm" type="button" onclick="remove_child_fields(' + child + ');">',
            '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button></div></div></div></div><div class="clear"></div>'
          );

        }

          childData.innerHTML = html.join('');
          objTo.appendChild(childData);
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

        if(isset($child_names)) {
            $count = count($child_names) - 1;
            for($i=0; $i < $count; $i++) { ?>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        child_fields(<?php $cm; ?>);
                    });
                </script>
                <?php
            }
        }
}

?>