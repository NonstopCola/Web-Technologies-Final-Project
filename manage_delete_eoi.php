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

// Job reference mapping
const JOB_REFERENCES = [
    'job-cybersecurity-specialist' => ['title' => 'Cyber-Security Specialist', 'code' => 'S9475'],
    'job-network-admin' => ['title' => 'Network Admin', 'code' => 'K9986'],
    'job-software-developer' => ['title' => 'Software Developer', 'code' => 'J7652']
];

function getJobTitle($jobRef) {
    return JOB_REFERENCES[$jobRef]['title'] ?? 'UNKNOWN';
}

function getCode($jobRef) {
    return JOB_REFERENCES[$jobRef]['code'] ?? 'UNKNOWN';
}

function santitizeOutput($data){
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './header.inc'; ?>
    <title>Confirm Deletion</title>
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
        // Has user submitted form to confirm deletion?
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete']) && isset($_POST['job_ref'])) {
            // Handle the deletion
            $jobRef = $_POST['job_ref'];

            // $delete_query safely
            $delete_query = "DELETE FROM eoi WHERE Job_Reference_Number = ?";
            $stmt = mysqli_prepare($conn, $delete_query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $jobRef);
                if (mysqli_stmt_execute($stmt)) {
                    // Add success message to session and redirect
                    $_SESSION['delete_success'] = true;
                    header("Location: manage.php");
                    exit();
                } else {
                    error_log("Error deleting records: " . mysqli_error($conn));
                    echo "<p class='error'>❌ Error deleting items. Contact administrator.</p>";
                }
            }
        } else if (isset($_GET['job_ref'])) {
            $jobRef = $_GET['job_ref'];
            
            // Get count of EOIs to be deleted
            $count_query = "SELECT COUNT(*) as count FROM eoi WHERE Job_Reference_Number = ?";
            $stmt = mysqli_prepare($conn, $count_query);
            if($stmt){
                mysqli_stmt_bind_param($stmt, "s", $jobRef);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $count = mysqli_fetch_assoc($result)['count'] ?? 0;
            
            if ($count > 0) {
                ?>
                <p class="warning"><i>⚠️ This action cannot be undone!</i></p>
                <fieldset>
                    <legend>Delete Entries</legend>
                    <p>You are about to delete <strong>all entries</strong> for:</p>
                    <p><strong>Position:</strong> <?= santitizeOutput(getJobTitle($jobRef)) ?></p>
                    <p><strong>Reference Code:</strong> <?= santitizeOutput(getCode($jobRef)) ?></p>
                    <p><strong>Number of EOIs to delete:</strong> <?= santitizeOutput($count) ?></p>

                    <div class="button-group">
                        <form action="./manage_delete_eoi.php" method="post" class="button-form">
                            <input type="hidden" name="job_ref" value="<?= santitizeOutput($jobRef) ?>">
                            <input type="hidden" name="confirm_delete" value="1">
                            <input type="submit" value="🗑 Delete All" class="delete">
                        </form>
                        <form action="./manage.php" method="get" class="button-form">
                            <input type="submit" value="Cancel" class="submit">
                        </form>
                    </div>
                </fieldset>
                <?php
            } else {
                echo "<p>No EOIs found for the selected position.</p>";
                echo "<p><a href='./manage.php' class='submit'>Back to Manage</a></p>";
            }
        }
        } else {
            echo "<p class='error'>❌ Invalid request.</p>";
            echo "<p><a href='./manage.php' class='submit'>Back to Manage</a></p>";
        }
        ?>
    </div>
    <?php include './footer.inc' ?>
</body>
</html>