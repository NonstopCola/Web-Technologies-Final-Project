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
                <input type="text" id="username" name="username" 
                pattern="[a-zA-Z0-9_]{3,20}" title="No Special Characters, between 3-20 characters" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" 
                pattern="[a-zA-Z0-9_]{7,20}", title="No Special Characters, between 7-20 characters" required>

                <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">
                <input type="submit" value="Register">
            </form>
        </section>

        <?php
            // Connects to the database
            require_once './settings.php';
            
            $require_not_login = true;
            include './redirect.inc';
            
            // Checks if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Server-side validation
                if (!isset($_POST['username'], $_POST['password'], $_POST['date']) ||
                    !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $_POST['username']) ||
                    strlen($_POST['password']) < 6){
                        echo "<input type='checkbox' id='close'>
                        <label for='close' id='failed'>Invalid username or password.</label>";
                        include './footer.inc';
                        exit();
                    }
                // Retrieves the username, password, and date from the form
                $new_username = trim($_POST['username']);
                $new_password = trim($_POST['password']);
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $date = trim($_POST['date']);

                // Checks the database for the username
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $new_username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                // Checks if the user is found
                if (!$user){
                    // If the user is not found, insert the new user into the database while setting the valid column to 0
                    $stmt = $conn->prepare("INSERT INTO users (username, password, valid, register_date) VALUES (?, ?, 0, ?)");
                    $stmt->bind_param("sss", $new_username, $hashed_password, $date);
                    $stmt->execute();
                    // Echos a success message if the query was successful
                    echo "<input type='checkbox' id='close'>
                    <label for='close' id='success'>Account created successfully.</label>";
                } else{
                    // Echos a failure message if the user is found
                    echo "<input type='checkbox' id='close'>
                        <label for='close' id='failed'>Registration failed: " . mysqli_error($conn) . "</label>";
                }
            }
            
            // Includes the footer
            include './footer.inc';
        ?>
    </body>
</html>