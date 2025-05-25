<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Registrations Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo" width="100" height="100">
            <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>Registrations</h1>

        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'registrations';
            include './nav.inc';
            
            require_once './settings.php';
            
            // Checks if a POST request has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Checks if the POST request was an approval or denial of a registration
                if (isset($_POST['approve'])){
                    // Gets the username from the POST request
                    $username = trim($_POST['username']);
                    // Updates the database to set the user as valid
                    $stmt = $conn->prepare("UPDATE users SET valid = 1 WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    // Checks if the query was successful
                    if ($stmt->affected_rows > 0){
                        // Displays a success message if it was successful
                        echo "<input type='checkbox' id='close'>
                            <label for='close' id='success'>User $username approved successfully.</label>";
                    } else{
                        // If it wasn't, displays a failure message
                        echo "<input type='checkbox' id='close'>
                            <label for='close' id='failed'>Approval failed: " . mysqli_error($conn) . "</label>";                    }
                }

                if (isset($_POST['deny'])){
                    // Gets the username from the POST request
                    $username = trim($_POST['username']);
                    // Updates the database to remove the registration request
                    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // Checks if the query was successful
                    if ($stmt->affected_rows > 0){
                        // Displays a success message if it was successful
                        echo "<input type='checkbox' id='close'>
                            <label for='close' id='success'>User $username denied successfully.</label>";
                    } else{
                        // If it wasn't, displays a failure message
                        echo "<input type='checkbox' id='close'>
                            <label for='close' id='failed'>Denial failed: " . mysqli_error($conn) . "</label>";
                    }
                }
            }

            // Queries the database for all users with valid = 0, which means they are pending approval
            $query = "SELECT * FROM users WHERE valid = 0";
            $result = mysqli_query($conn, $query);

            // Checks if the query was successful
            if ($result && mysqli_num_rows($result) > 0){
                echo "<h2 class='setMiddle'>Pending Registrations</h2>";
                // Loops through the results and displays them
                while ($row = mysqli_fetch_assoc($result)){
                    if ($row['register_date'] != null){
                        // Formats the date to be in the Australian fromat of dd/mm/yyyy
                        $formatted_date = date('d/m/Y', strtotime($row['register_date']));
                    }
                    // Displays the username, formatted date, and valid status
                    echo "<fieldset class='reg-entry'>";
                    echo "<legend>Registration Details for " . htmlspecialchars($row['username']) . "</legend>";
                    echo "<h2>Name: " . $row['username'] . "</h2>";
                    if ($row['register_date'] == null){
                        // If the date is null, displays a message
                        echo "<p>Registered: Null</p>";
                    } else{
                        // Otherwise, displays the formatted date
                        echo "<p>Registered: " . $formatted_date . "</p>";
                    }
                    echo "<p>Valid Status: False</p>";
                    // Creates a form for approving the registration
                    echo "<form method='POST' action='registrations.php' class='approve'>";
                    echo "<input type='hidden' name='username' value='" . $row['username'] . "'>";
                    echo "<input type='submit' name='approve' value='Approve'>";
                    echo "</form>";
                    // Creates a form for denying the registration
                    echo "<form method='POST' action='registrations.php' class='deny'>";
                    echo "<input type='hidden' name='username' value='" . $row['username'] . "'>";
                    echo "<input type='submit' name='deny' value='Deny'>";
                    echo "</form>";
                    echo "</fieldset>";
                }
            } elseif ($result && mysqli_num_rows($result) == 0){
                // If there are no pending registrations, displays a message
                echo "<h2 class='setMiddle'>No pending registrations found.</h2>";
                echo "<p class='setMiddle'>Please check back later.</p>";
            } else {
                // If the query failed, displays a failure message
                echo "<input type='checkbox' id='close'>
                    <label for='close' id='failed'>Query failed: " . mysqli_error($conn) . "</label>";
            }
            
            // Redirects to error page if an issue arises, sets it so a login is required
            $require_login = true;
            include './redirect.inc';
            

            // Includes the footer
            include './footer.inc';
        ?>
    </body>
</html>