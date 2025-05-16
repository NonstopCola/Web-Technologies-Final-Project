<?php
session_start();
require_once("settings.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // If a confirmation flag is set, actually delete the entry
    if (isset($_POST['confirm_delete'])) {
        $delete_query = "DELETE FROM eoi WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            echo "Record deleted successfully.";
            // Optionally redirect to manage page after deletion:
            // header("Location: manage.php");
            // exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        // Show confirmation prompt as you already do
        $query = "SELECT * FROM eoi WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            echo "<h2><b>Are you sure you want to delete this?</b></h2>";
            echo "<p><i>This cannot be undone.</i></p>";
            echo "<form action='delete_prompt.php' method='post'>";
            echo "<fieldset>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<input type='hidden' name='confirm_delete' value='1'>";  // Add this flag to know when to delete
            echo "<label><b>Name: </b></label>";
            echo "<label>" . $row['firstname'] . " " . $row['lastname'] . "</label><br>";
            echo "<label><b>Position: </b></label>";
            echo "<label>" . $row['position'] . " - <i>" . $row['refnumber'] . "</i></label><br>";
            echo "<label><b>Status: </b></label>";
            echo "<label>" . $row['status'] . "</label><br><br>";
            echo "<input type='submit' value='Delete' style='background:red;color:white;margin-right:10px;'>";
            echo "</fieldset>";
            echo "</form>";

            // Cancel button (back to manage.php or wherever you came from)
            echo "<form action='manage.php' method='get' style='display:inline;'>";
            echo "<input type='submit' value='Cancel'>";
            echo "</form>";
        } else {
            echo "Error: Application not found.";
        }
    }
} else {
    echo "Invalid request.";
}
?>