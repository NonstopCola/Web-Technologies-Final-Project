<!-- 5. HR manager queries (manage.php)
Create a web page manage.php that allows a manager to make the following queries of
the eoi table and returns a web page with the appropriate results.
• List all EOIs.
• List all EOIs for a particular position (given a job reference number).
• List all EOIs for a particular applicant given their first name, last name or both.
• Delete all EOIs with a specified job reference number
• Change the Status of an EOI. -->

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Manage Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>

        <h1>Manage</h1>
        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'manage';
            include './nav.inc';
            
            // Redirects to error page if an issue arises
            include './redirect.inc';

            // Includes the footer
            include './footer.inc';
        ?>
    </body>
</html>
