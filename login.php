<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Calls for the start of the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Login Page</title>
        <!-- Titled Page -->
    </head>
    <body>
        <div class="logoContainer">
            <img src="./images/final_logo.png" alt="logo of company" id="Logo">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>Login</h1>

        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'login';
            include './nav.inc';
        ?>

        <?php
            // Connects to the database
            require_once 'settings.php';
            
            $conn = mysqli_connect($host, $username, $password, $database);

            // Checks if the user has submitted the logout form
            if (isset($_POST['logout'])) {
                // Destroys the current session data (logs the user out)
                session_destroy();
                // Redirects the user to the index page
                header('Location: index.php');
                exit();
            }
            // Checks if the user is already logged in and prompts them to log out
            if (isset($_SESSION['username'])) {
                echo "<p>Welcome back, " . htmlspecialchars($_SESSION['username']) . "!</p>";
                echo "<p>Did you want to log out?</p>";
                echo "<form method='POST' action='login.php'>";
                echo "<input type='submit' value='Logout' name='logout'>";
                echo "</form>";
                exit();
            }
            else { // If the user is not logged in, display the login form
                echo "<form method='POST' action='login.php'>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' required>";
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<input type='submit' value='Login'>";
                echo "</form>";
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
                if ($user) {
                    $_SESSION['username'] = $user['username'];
                    header('Location: index.php');
                    exit();
                } else { // If the user is not found, display an error message
                    echo "<p>Invalid username or password.</p>";
                }
            }
        ?>
    </body>
</html>