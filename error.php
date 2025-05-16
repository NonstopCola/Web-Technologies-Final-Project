<?php
    session_start();
    // Redirects to homepage if there was no error
    if (!isset($_SESSION['errortype'])){
        header('Location: index.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Includes the header
            include './header.inc';
        ?>
        <title>Error Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo" width="100" height="100">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>Error</h1>

        <?php
            // Sets the active page for no navigation highlighting
            $activePage = 'Null';
            include './nav.inc';

            if ($_SESSION['errortype'] == 'authorisation'){
                // Displays an error message if the user is not logged in
                echo "<h2 class='setMiddle'>You do not have access to this page, please login to access it.</h2>";
            } elseif ($_SESSION['errortype'] == 'validation'){
                // Displays an error message if something went wrong with the form validation
                echo "<h2 class='setMiddle'>There was an error with the form submission.</h2>";
                echo "<p class='setMiddle'>The following error(s) occurred:</p>";
                echo "<ul class='setMiddle'>";
                foreach ($_SESSION['errors'] as $error){
                    // Displays each error message
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            } elseif ($_SESSION['errortype'] == 'over_authorised'){
                echo "<h2 class='setMiddle'>You are already logged in.</h2>";
                echo "<p class='setMiddle'>Please logout to access this page.</p>";
                echo "<form method='POST' action='login.php' class='setMiddle'>";
                echo "<input type='submit' value='Logout' name='logout'>";
                echo "</form>";
            }

            // Includes the footer
            include './footer.inc';

            // Unsets the error type and errors array so the error message does not persist
            $_SESSION['errortype'] = null;
            $_SESSION['errors'] = null;
        ?>
    </body>
</html>