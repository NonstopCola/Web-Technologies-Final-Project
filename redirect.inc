<?php
    // Sends to error page if any errors have been logged into the 'errors' array
    if (!empty($_SESSION['errors'])){
        $_SESSION['errortype'] = 'validation';
        header('Location: ./error.php');
        exit();
    }   elseif (!empty($require_login)){ // Sends to error page if the user is not logged in and the page requires it
        if (!isset($_SESSION['username'])) {
            $_SESSION['errortype'] = 'authorisation';
            header('Location: ./error.php');
            exit();
        }  
    }   elseif (!empty($require_not_login)){ // Sends to error page if the user is logged in when the page requires it not
        if (isset($_SESSION['username'])){
            $_SESSION['errortype'] = 'over_authorised';
            header('Location: ./error.php');
            exit();
        }
    }
?>