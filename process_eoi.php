<?php
require_once("settings.php")
//-- Allow access strictly by POST when data is posted to form not by typing in process_eoi URL
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php"); //Redirects back to apply page if accessed directly 
    exit();
}
//-- Creates a function that can be used to sanitise/clean all input data from form 
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//-- Retrieve data from form and sanatise the input using clean_input function 
$errors = [];
$job_reference = clean_input($_POST["job-ref"]);
$first_name = clean_input($_POST["firstName"]);
$last_name = clean_input($_POST["lastName"]);
$street = clean_input($_POST["streetAddress"]);
$state = clean_input($_POST["state"]);
$postcode = clean_input($POST["post-code"]);
$email = clean_input($POST["contact-email"]);
$phone_number = clean_input($POST["phone-number"]);
$required_skills = $POST["category"];
$other_skills = clean_input($POST["textarea"]);

//Validate to ensure that no required fields are empty 
//If there are any errors show an error page 