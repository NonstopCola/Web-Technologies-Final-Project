<?php
    // Doesn't allow anyone to access this page unless they are logged in
    if (!empty($_SESSION['errors'])){
        $_SESSION['errortype'] = 'validation';
        header('Location: error.php');
        exit();
    }   elseif (!empty($require_login)){
        if (!isset($_SESSION['username'])) {
            $_SESSION['errortype'] = 'authorisation';
            header('Location: error.php');
            exit();
        }  
    } 
?>