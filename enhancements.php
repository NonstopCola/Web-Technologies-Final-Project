<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Enhancements Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>Enhancements</h1>
        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'enhancements';
            include './nav.inc';

            // Doesn't allow anyone to access this page unless they are logged in
            if (!isset($_SESSION['username'])) {
                header('Location: index.php');
                exit();
            }
            
            // Sets up the footer
            include './footer.inc';
        ?>
    </body>
</html>