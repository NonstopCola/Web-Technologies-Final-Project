  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php
    session_start();
    require_once("settings.php");
    include './header.inc';

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    ?>

<title>Confirm Deletion</title>
</head>
  <body>
    <div class="logoContainer">
      <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    </div>

    <!-- Include nav bar, set manage as active and make sure user is signed in -->
    <?php 
    // Sets the active page for navigation highlighting
    $activePage = 'manage';
    include './nav.inc'; 
    // Doesn't allow anyone to access this page unless they are logged in
    if(!isset($_SESSION['username'])){
      header('Location: index.php');
      exit();
    }
    ?>

    <!-- WIP div container for page -->
    <div class="apply_container">
        <h2><b>Are you sure you want to <i>delete</i> this?</b></h2>
        <p><i>This cannot be undone.</i></p>

    <?php
    // Get the POST submission from manage.php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        
        // Set the id variable from post 
        $id = $_POST['id'];

        // If a confirmation flag is set, delete the entry and redirect back to manage.php
        if (isset($_POST['confirm_delete'])) {
            $delete_query = "DELETE FROM eoi WHERE id = $id";
            if (mysqli_query($conn, $delete_query)) {
                // Commented out section until we add a continue button, otherwise there is no point in showing something nobody will see!
                // echo "<p>✅ Item deleted successfully.</p>";
                header("Location: manage.php");
                exit();
            } else {
                error_log("Error deleting record: " . mysqli_error($conn, $delete_query));
                echo "<p>❌ Error deleting item. Contact administrator.</p>";
            }
        }
        // If we haven't got a flag yet, then we should show the information we want deleted!
        else {
            // Show confirmation prompt as you already do
            $query = "SELECT * FROM eoi WHERE id = $id";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);

            // Show information of row for user
            if ($row) {
                echo "<form action='manage_delete_eoi.php' method='post'>";
                echo "<fieldset>";
                //echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<input type='hidden' name='confirm_delete' value='1'>";
                echo "<label><b>Name: </b></label>";
                echo "<label>" . $row['firstname'] . " " . $row['lastname'] . "</label><br>";
                echo "<label><b>Position: </b></label>";
                echo "<label>" . $row['position'] . " - <i>" . $row['refnumber'] . "</i></label><br>";
                echo "<label><b>Status: </b></label>";
                echo "<label>" . $row['status'] . "</label><br><br>";
                echo "<input type='submit' value='🗑 Delete' class='delete'>";
                echo "</form>";

                // Cancel button (back to manage.php or wherever you came from)
                echo "<form action='manage.php' method='post' style='display:inline;'>";
                echo "<input type='submit' value='Cancel' class='submit'>";
                echo "</form>";
                echo "</fieldset>";

            } else {
                error_log("Failed to get row for manage_delete. Error Code:  " .  mysqli_error($row););
                echo "<p>Item to delete not found 😢</p>";
            }
        }
    } else {
        error_log("Failed to lookup id number from database in manage_delete. Error Code: "  . mysqli_error($_POST['id']));
        echo "<p>❌ Failed to find item in database.</p>";
    }
?>
    </div>
        <?php include './footer.inc' ?>
    </body>
</html>