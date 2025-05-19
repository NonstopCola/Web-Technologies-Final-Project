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
        <h1>Enhancements Page</h1>
        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'enhancements';
            include './nav.inc';

            // Redirects to error page if an issue arises, sets it so a login is required
            $require_login = true;
            include './redirect.inc';
        ?>
        <h2 class='setMiddle'>Enhancements</h2>
        <section id="manageEnhancements">
            <h3 class='setMiddle'>Management</h3>
        
        </section>
        <?php
            // Sets up the footer
            include './footer.inc';
        ?>
    </body>
</html>