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

            if (!$conn) {
                // If the connection fails, display an error message
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql_table = "users";
            $create_table = "CREATE TABLE IF NOT EXISTS `users` (
                `username` varchar(20) NOT NULL PRIMARY KEY,
                `password` varchar(20) NOT NULL,
                `valid` tinyint(1) NOT NULL,
                `register_date` date DEFAULT NULL
                )";
            
            if (!mysqli_query($conn, $create_table)){
                die("Table creation failed: " . mysqli_error($conn));
            }

            mysqli_query($conn, $create_table);

            $query = "SELECT * FROM users WHERE username = 'admin' AND password = '123'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                // If the admin user does not exist, create it
                $create_admin = "INSERT INTO users (username, password, valid) VALUES ('admin', '123', 1)";
                mysqli_query($conn, $create_admin);
            }

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
                echo "<h2 class='setMiddle'>Sign out of your account</h2>";
                echo "<section id='logoutForm'>";
                echo "<h3>Welcome back, " . htmlspecialchars($_SESSION['username']) . "!</h3>";
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
                echo "<h2 class='setMiddle'>Sign in to your account</h2>";
                echo "<section id='login_register_Form'>";
                echo "<h3 class='setMiddle'>Details</h3>";
                echo "<form method='POST' action='login.php'>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' required>";
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<input type='submit' value='Login'>";
                echo "</form>";
                echo "</section>";
                echo "<a class='setMiddle' href='register.php'>Want to create an account?</a>";
                // Includes the footer
                include './footer.inc';

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
                    if ($user && $user['valid'] == 1) {
                        $_SESSION['username'] = $user['username'];
                        header('Location: index.php');
                        exit();
                    } else { // If the user is not found, display an error message
                        echo "<input type='checkbox' id='close'>
                        <label for='close' id='failed'>Invalid username or password.</label>"; 
                    }
                }
            }
        ?>
    </body>
</html>