<?php
//-- Allow access strictly by POST when data is posted to form not by typing in process_eoi URL
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.php"); //Redirects back to apply page if accessed directly 
    exit();
}
//-- Sanitise the input from the user  
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}