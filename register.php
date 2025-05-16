<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Register Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo" width="100" height="100">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>

        <h1>Register</h1>

        <?php
            // Sets the active page for no navigation highlighting
            $activePage = null;
            include './nav.inc';
        ?>
        
        <h2 class='setMiddle'>Register an account</h2>

        <!-- Creates a form that asks for a username and password, in addition giving the current date when submitted -->
        <section id="login_register_Form">
            <h3 class='setMiddle'>Details</h3>
            <form method="POST" action="register.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <input type="submit" value="Register">
            </form>
        </section>

        <?php
            // Connects to the database
            require_once './settings.php';
            
            $conn = mysqli_connect($host, $username, $password, $database);

            $require_not_login = true;
            include './redirect.inc';
            
            // Checks if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Retrieves the username, password, and date from the form
                $new_username = trim($_POST['username']);
                $new_password = trim($_POST['password']);
                $date = trim($_POST['date']);

                // Checks the database for the username and password
                $query = "SELECT * FROM users WHERE username = '$new_username' AND password = '$new_password'";
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);

                // Checks if the user is found
                if (!$user){
                    // If the user is not found, insert the new user into the database while setting the valid column to 0
                    $query = "INSERT INTO users (username, password, valid, register_date) VALUES ('$new_username', '$new_password', 0, '$date')";
                    $result = mysqli_query($conn, $query);
                    // Echos a success message if the query was successful
                    echo "<input type='checkbox' id='close'>
                    <label for='close' id='success'>Account created successfully.</label>";
                } else{
                    // Echos a failure message if the user is found
                    echo "<input type='checkbox' id='close'>
                    <label for='close' id='failed'>Invalid account.</label>";
                }
            }
            
            // Includes the footer
            include './footer.inc';
        ?>
    </body>
</html>