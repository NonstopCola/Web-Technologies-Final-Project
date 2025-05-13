<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
    </head>
    <body>
        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'manage';
            include './nav.inc';
            // Doesn't allow anyone to access this page unless they are logged in
            if (!isset($_SESSION['username'])) {
                header('Location: index.php');
                exit();
            }
        ?>
    </body>
</html>
