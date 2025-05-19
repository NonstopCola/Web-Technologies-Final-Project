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

  <title>Updating...</title>
</head>
  <body>
    <div class="logoContainer">
    <?php
    // Check if this is a POST request and if the necessary data is provided
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        // Get inputs
        $status = trim($_POST['status'] ?? '');
        $id = $_POST['id'] ?? '';

        // Simple validation
        if ($status && $id) {
            // Prepare and execute the query securely
            $updateQuery = $conn->prepare("UPDATE eoi SET status = ? WHERE id = ?");
            if ($updateQuery) {
                // Bind values (strings and int) new placeholder
                $updateQuery->bind_param("si", $status, $id);
                // Get query from database
                if ($updateQuery->execute()) {
                    // Show success
                    echo "<h2>✅ Update Sucessful!</h2>";
                    echo "<p>Status was successfully updated to '$status'.</h2>";
                    // Create back button
                    echo "<form action='manage.php' method='post' style='display:inline;'>";
                    echo "<input type='submit' value='Go Back' class='submit'>";
                    echo "</form>";
                } else {
                    echo "<p>❌ Failed to update EOI. Please try again.</p>";
                }
                // Closes query
                $updateQuery->close();
            } else {
                echo "<p>❌ Database error: could not prepare statement.</p>";
            }
        }
    }
    ?>
    </body>
</html>