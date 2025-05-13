<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Calls for the start of the session and includes the header
            session_start();
            include './header.inc';
            if (isset($_SESSION['username'])) {
                echo "<title>Logout Page</title>";
            } else {
                echo "<title>Login Page</title>";
            }
            // Login/Logout page title
        ?>
    </head>
    <body>
        <div class="logoContainer">
            <img src="./images/final_logo.png" alt="logo of company" id="Logo">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>

        <?php
            // Displays the title based on whether the user is logged in or not
            if (isset($_SESSION['username'])) {
                echo "<h1>Logout</h1>";
            } else {
                echo "<h1>Login</h1>";
            }

            // Sets the active page for navigation highlighting
            $activePage = 'login';
            include './nav.inc';

            // Connects to the database
            require_once './settings.php';
            
            $conn = mysqli_connect($host, $username, $password, $database);

            // Checks if the user has submitted the logout form
            if (isset($_POST['logout'])) {
                // Destroys the current session data (logs the user out)
                session_destroy();
                // Redirects the user to the index page
                header('Location: index.php');
                exit();
            }

            // Checks if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Retrieves the username and password from the form
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                
                // Checks the database for the username and password
                $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);
                $user = mysqli_fetch_assoc($result);

                // If the user is found, set the session variable and redirect to index.php
                if ($user['valid'] == 1) {
                    $_SESSION['username'] = $user['username'];
                    header('Location: index.php');
                    exit();
                } else { // If the user is not found, display an error message
                    echo "<p id='failed'>Invalid username or password.</p>";
                }
            }

            // Checks if the user is already logged in and prompts them to log out
            if (isset($_SESSION['username'])) {
                echo "<section id='logoutForm'>";
                echo "<p>Welcome back, " . htmlspecialchars($_SESSION['username']) . "!</p>";
                echo "<p>Did you want to log out?</p>";
                echo "<form method='POST' action='login.php'>";
                echo "<input type='submit' value='Logout' name='logout'>";
                echo "</form>";
                echo "</section>";
                // Includes the footer
                include './footer.inc';
                exit();
            }
            else { // If the user is not logged in, display the login form
                echo "<section id='login_register_Form'>";
                echo "<form method='POST' action='login.php'>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' required>";
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<input type='submit' value='Login'>";
                echo "</form>";
                echo "</section>";
                echo "<section id='login_register_Form'>";
                echo "<a href='register.php'>Want to create a manager account?</a>";
                echo "</section>";
                // Includes the footer
                include './footer.inc';
            }
        ?>
    </body>
</html>