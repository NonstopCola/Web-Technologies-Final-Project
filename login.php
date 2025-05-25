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

            // Checks if the system has intialized the login attempts
            if (!isset($_SESSION['login_attempts'])) {
                // Initializes the login attempts, attempt time, and block time
                $_SESSION['login_attempts'] = 0;
                $_SESSION['first_attempt_time'] = time();
                $_SESSION['login_blocked_until'] = 0;
            } elseif (time() < $_SESSION['login_blocked_until']) { // Checks if the user is blocked
                // Calculates the remaining time until the user is unblocked
                $remaining = $_SESSION['login_blocked_until'] - time();
                // Displays an error message if the user is blocked
                echo "<input type='checkbox' id='close'><label for='close' id='failed'>Too many failed login attempts. Please wait {$remaining} seconds before trying again.</label>";
                echo "<h2 class='setMiddle'>You have made {$_SESSION['login_attempts']} failed login attempts.</h2>";
                echo "<p class='setMiddle'>Please wait before trying again.</p>";
                include './footer.inc';
                exit();
            } elseif (time() - $_SESSION['first_attempt_time'] > 60) { // Checks if the first attempt was made more than 60 seconds ago
                // Resets the login attempts
                $_SESSION['login_attempts'] = 0;
                $_SESSION['first_attempt_time'] = time();
            }

            $create_table = "CREATE TABLE IF NOT EXISTS `users` (
                `username` varchar(20) NOT NULL PRIMARY KEY,
                `password` varchar(255) NOT NULL,
                `valid` tinyint(1) NOT NULL,
                `register_date` date DEFAULT NULL
                )";
            
            if (!mysqli_query($conn, $create_table)){
                echo "<input type='checkbox' id='close'>
                    <label for='close' id='failed'>Table creation failed: " . mysqli_error($conn) . "</label>";            
            }

            mysqli_query($conn, $create_table);

            $admin_username = 'admin';

            // Checks if the admin user exists in the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $admin_username);
            $stmt->execute();
            $result = $stmt->get_result();

            $admin_password = password_hash('123', PASSWORD_DEFAULT);

            if (mysqli_num_rows($result) == 0) {
                // If the admin user does not exist, create it
                $stmt = $conn->prepare("INSERT INTO users (username, password, valid) VALUES (?, ?, 1)");
                $stmt->bind_param("ss", $admin_username, $admin_password);
                $stmt->execute();
            }

            // Checks if the user has submitted the logout form
            if (isset($_POST['logout'])) {
                // Destroys the current session data (logs the user out)
                session_destroy();
                // Redirects the user to the index page
                header('Location: ./index.php');
                exit();
            }



            // Checks if the user is already logged in and prompts them to log out
            if (isset($_SESSION['username'])) {
                echo "<h2 class='setMiddle'>Log out of your account</h2>";
                echo "<section id='logoutForm'>";
                echo "<h3>Welcome back, " . htmlspecialchars($_SESSION['username']) . "!</h3>";
                echo "<p>Did you want to log out?</p>";
                echo "<form method='POST' action='./login.php'>";
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
                echo "<form method='POST' action='./login.php'>";
                echo "<label for='username'>Username:</label>";
                echo "<input type='text' id='username' name='username' required>";
                echo "<label for='password'>Password:</label>";
                echo "<input type='password' id='password' name='password' required>";
                echo "<input type='submit' value='Login'>";
                echo "</form>";
                echo "</section>";
                echo "<a class='setMiddle' href='./register.php'>Want to create an account?</a>";
                // Includes the footer
                include './footer.inc';
            }

            // Checks if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Retrieves the username and password from the form
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);
                
                // Increments the login attempts by 1
                $_SESSION['login_attempts']++;
                    
                // Checks if the user has made 3 failed login attempts
                if ($_SESSION['login_attempts'] >= 3){
                    // Blocks the user for 60 seconds
                    $_SESSION['login_blocked_until'] = time() + 60;
                    // Reloads the page to show the error message
                    header('Location: ./login.php');
                    exit();
                }

                // Checks the database for the username
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                // If the user is found, set the session variable and redirect to index.php
                if ($user && $user['valid'] == 1 && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['username'] = $user['username'];
                    // Resets the login attempts and block time
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['first_attempt_time'] = time();
                    $_SESSION['login_blocked_until'] = 0;
                    header('Location: ./index.php');
                    exit();
                } else { // If the user is not found, display an error message
                    if ($_SESSION['login_attempts'] < 3){
                        echo "<input type='checkbox' id='close'>
                            <label for='close' id='failed'>Invalid username or password.</label>";
                    }  
                }
            }
        ?>
    </body>
</html>