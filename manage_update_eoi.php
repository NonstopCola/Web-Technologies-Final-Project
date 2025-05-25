<?php
session_start();
require_once("./settings.php");

// Access control
if (!isset($_SESSION['username'])) {
    header('Location: ./index.php');
    exit();
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Updating EOI Status</title>
    <?php include './header.inc'; ?>
</head>
<body class="manage">
    <div class="logoContainer">
        <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    </div>

    <?php
    $activePage = 'manage';
    include './nav.inc';
    ?>

    <div class="apply_container">
        <?php
        // Check if this is a POST request and if the necessary data is provided
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
            // Get inputs with proper case matching from manage.php
            $status = trim($_POST['Status'] ?? '');
            $id = $_POST['id'] ?? '';

            // Simple validation
            if ($status && $id) {
                // Prepare and execute the query securely
                $updateQuery = $conn->prepare("UPDATE eoi SET Status = ? WHERE EOInumber = ?");
                if ($updateQuery) {
                    // Bind values (strings and int)
                    $updateQuery->bind_param("si", $status, $id);
                    
                    // Execute query
                    if ($updateQuery->execute()) {
                        ?>
                        <div class="success-message">
                            <h2>✅ Update Successful!</h2>
                            <p>Status was successfully updated to '<?= htmlspecialchars($status) ?>'.</p>
                        </div>
                        <?php
                    } else {
                        error_log("Failed to execute EOI update. Error: " . $updateQuery->error);
                        ?>
                        <div class="error-message">
                            <h2>❌ Update Failed</h2>
                            <p>Failed to update the EOI status. Please contact administrator.</p>
                        </div>
                        <?php
                    }
                    // Close query
                    $updateQuery->close();
                } else {
                    error_log("Failed to prepare EOI update statement. Error: " . $conn->error);
                    ?>
                    <div class="error-message">
                        <h2>❌ System Error</h2>
                        <p>A database error occurred. Please contact administrator.</p>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="error-message">
                    <h2>❌ Invalid Input</h2>
                    <p>Missing required information to update the EOI.</p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="error-message">
                <h2>❌ Invalid Request</h2>
                <p>Invalid request method or missing update parameter.</p>
            </div>
            <?php
        }
        ?>

        <div class="button-group">
            <form action="./manage.php" method="get" class="button-form">
                <input type="submit" value="Back to Manage" class="submit">
            </form>
        </div>
    </div>

    <?php include './footer.inc'; ?>
</body>
</html>