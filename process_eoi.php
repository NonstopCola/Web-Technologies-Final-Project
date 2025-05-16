<?php
require_once("settings.php")
//-- Allow access strictly by POST when data is posted to form not by typing in process_eoi URL
if ($_SERVER["REQUEST_METHOD"] != "POST") {
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

//If table doesn't exist, create table 
$sql_table = "eoi"
$create_table = "CREATE TABLE IF NOT EXISTS $sql_table (
        EOInumber VARCHAR(20) PRIMARY KEY, 
        Job_Reference_Number VARCHAR(15), 
        First_Name VARCHAR(20), 
        Last_Name VARCHAR(20), 
        Street_Address VARCHAR(40), 
        Suburb VARCHAR(40), 
        State VARCHAR(3), 
        Postcode CHAR(4), 
        Email_Address VARCHAR(40), 
        Phone_Number VARCHAR(12), 
        Skills VARCHAR(20), 
        Other_Skills TEXT 
        Status ENUM('New', 'In Progress', 'Finalised') DEFAULT 'New');"
mysqli_query($conn, $create_table);

//-- Retrieve data from form and sanatise the input using clean_input function & add any errors to $errors[] array
$errors = [];
$job_reference = clean_input($_POST["job-ref"]);
$first_name = clean_input($_POST["firstName"]);
$last_name = clean_input($_POST["lastName"]);
$street = clean_input($_POST["streetAddress"]);
$state = clean_input($_POST["state"]);
$postcode = clean_input($_POST["post-code"]);
$email = clean_input($_POST["contact-email"]);
$phone_number = clean_input($_POST["phone-number"]);
if (isset($_POST["category"])) {
    $clean_required_skills = array_map('clean_input', $_POST["category"]); //cleans each item in array/checkbox selection & assigns to clean_required_skills
    $required_skills = implode(", ", $clean_required_skills) //converts array into string for db table and assigns value to required_skills
} else {
    $required_skills = "";//if not, assign an empty string
}
if (isset($_POST["textarea"])) {
    $other_skills = clean_input($_POST["textarea"]); //if input exists assign to other_skills
} else {
    $other_skills = ""; //if no input, assign empty 
}

//Display error page, if any errors occur 
if 

//Insert data from user into eoi table 


//Show confirmation & include EOInumber 
$EOInumber = uniqid('EOI') //uniqid() explanation from W3 Schools: https://www.w3schools.com/Php/func_misc_uniqid.asp

