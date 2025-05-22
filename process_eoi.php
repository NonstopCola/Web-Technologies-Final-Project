<?php
//!! 
// Postcode: Asked AI best type for 4-digit Australian postcode — CHAR/VARCHAR better than INT to preserve leading 0s and avoid arithmetic.
// Required skills: Asked AI how to store multiple checkbox values — separate columns not scalable, use one VARCHAR field with comma-separated values.
// Convert array to string using implode(): https://www.w3schools.com/PHP/func_string_implode.asp
// Clean array input with array_map(): https://www.w3schools.com/php/func_array_map.asp
// Date validation using checkdate(): https://www.w3schools.com/php/func_date_checkdate.asp
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("settings.php");
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

//-- If table doesn't exist, create table 
// fix auto increment 
$sql_table = "eoi";
$create_table = "CREATE TABLE IF NOT EXISTS $sql_table (
        EOInumber INT(11) AUTO_INCREMENT PRIMARY KEY, 
        Job_Reference_Number VARCHAR(35), 
        First_Name VARCHAR(20), 
        Last_Name VARCHAR(20), 
        Street_Address VARCHAR(40), 
        Suburb VARCHAR(40), 
        State VARCHAR(3), 
        Postcode CHAR(4), 
        Email_Address VARCHAR(40), 
        Phone_Number VARCHAR(12), 
        Skills VARCHAR(255), 
        Other_Skills TEXT, 
        Status ENUM('New', 'In Progress', 'Finalised') DEFAULT 'New');";

if (!mysqli_query($conn, $create_table)) {
    die("Table creation failed: " . mysqli_error($conn));
}

//-- Retrieve data from form and sanatise the input using clean_input function & add any errors to $errors[] array
$_SESSION['errors'] = []; // -- Initialises the error array to be used to redirect to errors page 

$job_reference = clean_input($_POST["job-ref"]);
$first_name = clean_input($_POST["firstName"]);
$last_name = clean_input($_POST["lastName"]);
$dob = clean_input($_POST["date-of-birth"]);
$gender = clean_input($_POST["gender"]);
$street = clean_input($_POST["streetAddress"]);
$suburb = clean_input($_POST["suburb"]);
$state = clean_input($_POST["state"]);
$postcode = clean_input($_POST["post-code"]);
$email = clean_input($_POST["contact-email"]);
$phone_number = clean_input($_POST["phone-number"]);
$other_skills = clean_input($_POST["textarea"]);

$clean_required_skills = [];
if (isset($_POST["category"])) {
    $clean_required_skills = array_map('clean_input', $_POST["category"]); //cleans each item in array/checkbox selection & assigns to clean_required_skills
    $required_skills = implode(", ", $clean_required_skills); //converts array into string for db table and assigns value to required_skills
} else {
    $required_skills = "";//if not, assign an empty string
}

// -- Server Side Validation 
$valid_job_references = ['job-network-admin', 'job-software-developer', 'job-cybersecurity-specialist'];
if (!in_array($job_reference, $valid_job_references)) {
    $_SESSION['errors'][] = "Invalid Job Reference Selected";
}
if (!preg_match("/^[a-zA-Z]{1,20}$/", $first_name)) {
    $_SESSION['errors'][] = "First Name must contain only alphabetic letters and be up to 20 characters long";
}
if (!preg_match("/^[a-zA-Z]{1,20}$/", $last_name)) {
    $_SESSION['errors'][] = "Last Name must contain only alphabetic letters and be up to 20 characters long";
}
// For Date oF birth ive taken into consideration the range between 1950 - 2007
if (empty($dob)) {
    $_SESSION['errors'][] = "Date of Birth is required";
} else {
    $date_aspects = explode('-', $dob);
    if( count($date_aspects) == 3 && checkdate((int)$date_aspects[1], $date_aspects[2],$date_aspects[0])) {
        $year = (int)$date_aspects[0];
        if ($year < 1950 || $year > 2007) {
            $_SESSION['errors'][] = "Date of Birth Year must be between 1950 and 2007";
        }
    } else {
        $_SESSION['errors'][] = "Invalid Date of Birth format. Please use YYYY-MM-DD.";
    }
}
$valid_genders = ['male', 'female', 'other'];
if (!in_array($gender, $valid_genders)) {
    $_SESSION['errors'][] = "Invalid Gender Selected";
}
if (!preg_match("/^[a-zA-Z0-9 ]{1,40}$/", $street)) {
    $_SESSION['errors'][] = "Street Address must contain letters, numbers, spaces and be up to 40 characters long";
}
if (!preg_match("/^[a-zA-Z ]{1,40}$/", $suburb)) {
    $_SESSION['errors'][] = "Street Address must contain letters, numbers, spaces and be up to 40 characters long";
}
$valid_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (!preg_match("/^[a-zA-Z ]{1,40}$/", $state)) {
    $_SESSION['errors'][] = "Invalid State Selected";
}
if (!preg_match("/^(0[2-9][0-9]{2}|[1-8][0-9]{3}|9[1-8][0-9]{2}|99[0-3][0-9]|994[0-4])$/", $postcode)) {
    $_SESSION['errors'][] = "Post Code must be a 4-digit number between 0200 and 9944";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors'][] = "Invalid Email Address format";
}
if (!preg_match("/^[0-9 ]{8,12}$/", $phone_number)) {
    $_SESSION['errors'][] = "Phone Number must contain 8 to 12 digits or spaces";
}
$valid_required_skills = ['windows-server', 'cloudfare-zero', 'python', 'powershell']; 
foreach ($clean_required_skills as $skill_item) {
    if (!in_array($skill_item, $valid_required_skills)) {
        $_SESSION['errors'][] = "Invalid skill selected: '{$skill_item}' is not a recognised option";
    }
}
if (empty($required_skills) && empty($other_skills)) {
    $_SESSION['errors'][] = "Please select at least 1 required skill and describe any other skills relevant";
}
//-- Redirects if any errors have occurred
include './redirect.inc';

//-- Insert data from user into eoi table 
$insert_data = "INSERT INTO $sql_table (   
            Job_Reference_Number, First_Name, Last_Name,Street_Address, Suburb, State, Postcode, Email_Address, Phone_Number, Skills, Other_Skills
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; //11 Placholders used to prevent SQL injection 

$stmt = mysqli_prepare($conn, $insert_data);
if ($stmt) { 
    mysqli_stmt_bind_param($stmt, 'sssssssssss', $job_reference, $first_name, $last_name, $street, $suburb, $state,
    $postcode, $email, $phone_number, $required_skills, $other_skills);
    $result = mysqli_stmt_execute($stmt); 
    if (!$result) {
        die("Data upload Failed: " . mysqli_error($conn));
    }
    // -- Include EOI number in session for index.php success message 
    $_SESSION['EOInumber'] = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);
} else {
    die("Prepared statement failed: " . mysqli_error($conn));
}
mysqli_close($conn);

// -- Success redirection to index.php
header("Location: index.php");
exit;
?>