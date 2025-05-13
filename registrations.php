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

            $conn = mysqli_connect($host, $username, $password, $database);
            
            // Checks if a POST request has been submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Checks if the POST request was an approval or denial of a registration
                if (isset($_POST['approve'])){
                    // Gets the username from the POST request
                    $username = trim($_POST['username']);
                    // Updates the database to set the user as valid
                    $query = "UPDATE users SET valid = 1 WHERE username = '$username'";
                    $result = mysqli_query($conn, $query);
                    // Checks if the query was successful
                    if ($result){
                        // Displays a success message if it was successful
                        echo "<p id='success'>User $username approved successfully.</p>";
                    } else{
                        // If it wasn't, displays a failure message
                        echo "<p id='failed'>Failed to approve user $username.</p>";
                    }
                }
                if (isset($_POST['deny'])){
                    // Gets the username from the POST request
                    $username = trim($_POST['username']);
                    // Updates the database to remove the registration request
                    $query = "DELETE FROM users WHERE username = '$username'";
                    $result = mysqli_query($conn, $query);
                    // Checks if the query was successful
                    if ($result){
                        // Displays a success message if it was successful
                        echo "<p id='success'>User $username denied successfully.</p>";
                    } else{
                        // If it wasn't, displays a failure message
                        echo "<p id='failed'>Failed to deny user $username.</p>";
                    }
                }
            }

            // Queries the database for all users with valid = 0, which means they are pending approval
            $query = "SELECT * FROM users WHERE valid = 0";
            $result = mysqli_query($conn, $query);

            // Checks if the query was successful
            if ($result){
                // Creates a table to display the results
                echo "<section id='regmanage'>";
                echo "<table class='empty'>";
                echo "<tr>";
                echo "<th>Username</th>";
                echo "<th>Application Date</th>";
                echo "<th>Valid</th>";
                echo "<th class='empty'></th>";
                echo "<th class='empty'></th>";
                echo "</tr>";
                // Loops through the results and displays them in the table
                while ($row = mysqli_fetch_assoc($result)){
                    // Formats the date to be in the Australian fromat of dd/mm/yyyy
                    $formatted_date = date('d/m/Y', strtotime($row['register_date']));
                    echo "<tr>";
                    // Displays the username, formatted date, and valid status
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $formatted_date . "</td>";
                    echo "<td>" . $row['valid'] . "</td>";
                    echo "<td>";
                    // Creates a form for approving the registration
                    echo "<form method='POST' action='registrations.php' class='approve'>";
                    echo "<input type='hidden' name='username' value='" . $row['username'] . "'>";
                    echo "<input type='submit' name='approve' value='Approve'>";
                    echo "</form>";
                    echo "</td>";
                    echo "<td>";
                    // Creates a form for denying the registration
                    echo "<form method='POST' action='registrations.php' class='deny'>";
                    echo "<input type='hidden' name='username' value='" . $row['username'] . "'>";
                    echo "<input type='submit' name='deny' value='Deny'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</section>";
            } else{
                // If the query failed, displays a failure message
                echo "<p id='failed'>No registrations found.</p>";
            }
            
            // Doesn't allow anyone to access this page unless they are logged in
            if (!isset($_SESSION['username'])) {
                header('Location: index.php');
                exit();
            }

            // Includes the footer
            include './footer.inc';
        ?>
    </body>
</html>