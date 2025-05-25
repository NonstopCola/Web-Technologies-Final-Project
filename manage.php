<!-- 5. HR manager queries (manage.php)
Create a web page manage.php that allows a manager to make the following queries of
the eoi table and returns a web page with the appropriate results.
FIX:

TODO:
✔ List all EOIs.
✔ List all EOIs for a particular position (given a job reference number).
✔ List all EOIs for a particular applicant given their first name, last name or both.
✔ Delete all EOIs with a specified job reference number
✔ Change the Status of an EOI. >
-->

<?php
require_once("settings.php");
session_start();

// Reset the filters if the user clicks "Show All" and deactives the "Delete All" button
if (isset($_POST['show_all'])) {
    header("Location: manage.php");
    exit();
}

// FUNCTIONS
// Used to Quickly sanitize filter inputs
function sanitizeInput($data) {
  return htmlspecialchars(trim($data));
}

// Get job title (for displaying to user)
function getJobTitle($jobRef) {
  return JOB_REFERENCES[$jobRef]['title'] ?? 'UNKNOWN';
}

// Get job code
function getCode($jobRef) {
  return JOB_REFERENCES[$jobRef]['code'] ?? 'UNKNOWN';
}

// Get job name 
function getJob($code) {
  foreach (JOB_REFERENCES as $ref => $data) {
      if ($data['code'] === $code) {
          return $ref;
      }
  }
  return 'UNKNOWN';
}

// Initialize filters
$filters = [
    'First_Name' => sanitizeInput($_POST['First_Name'] ?? ''),
    'Last_Name' => sanitizeInput($_POST['Last_Name'] ?? ''),
    'Job_Reference_Number' => sanitizeInput($_POST['Job_Reference_Number'] ?? ''),
    'Job_Code' => sanitizeInput($_POST['Job_Code'] ?? ''),
    'Status' => sanitizeInput($_POST['Status'] ?? '')
];

// Initialize sort order tracking in the session
if (!isset($_SESSION['sort_order'])) {
    $_SESSION['sort_order'] = [
        'First_Name' => 'ASC',
        'Last_Name' => 'ASC',
        'Job_Reference_Number' => 'ASC',
        'Status' => 'ASC',
    ];
}

if (!isset($_SESSION['sort_state'])) {
    $_SESSION['sort_state'] = [
        'First_Name' => 0,
        'Last_Name' => 0,
        'Job_Reference_Number' => 0,
        'Status' => 0
    ];
}

// Job reference mapping - This changes the job reference number to a job title and code which we can use 
const JOB_REFERENCES = [
    'job-cybersecurity-specialist' => ['title' => 'Cyber-Security Specialist', 'code' => 'S9475'],
    'job-network-admin' => ['title' => 'Network Admin', 'code' => 'K9986'],
    'job-software-developer' => ['title' => 'Software Developer', 'code' => 'J7652']
];

// Gets EOIs from database using input from filters
function getEOIs($conn, $filters=[]) {
    $query = "SELECT * FROM eoi WHERE 1=1";
    $params = [];
    
    if(!empty($filters["First_Name"])) {
        $query .= " AND First_Name LIKE ?";
        $params[] = "%" . $filters["First_Name"] . "%";
    }
    if(!empty($filters["Last_Name"])) {
        $query .= " AND Last_Name LIKE ?";
        $params[] = "%" . $filters["Last_Name"] . "%";
    }
    if (!empty($filters["Job_Reference_Number"])) {
        $query .= " AND Job_Reference_Number = ?";
        $params[] = $filters["Job_Reference_Number"];
    }
    if (!empty($filters["Job_Code"])) {
        $jobRef = getJob($filters["Job_Code"]);
        if ($jobRef !== 'UNKNOWN') {
            $query .= " AND Job_Reference_Number = ?";
            $params[] = $jobRef;
        } else {
            return false;
        }
    }

    // Sorting added
    if (!empty($filters['sort'])) {
        $allowedSortFields = ['First_Name', 'Last_Name', 'Job_Reference_Number', 'Status'];
        $sortParts = explode(' ', $filters['sort']);
        if (in_array($sortParts[0], $allowedSortFields) && (count($sortParts) === 1 || in_array($sortParts[1], ['ASC', 'DESC']))) {
            $query .= " ORDER BY " . $filters['sort'];
        } else {
            error_log("Invalid sort field: " . $filters['sort']);
            return false; // Invalid sort field
        }
    } else {
        $query .= " ORDER BY EOInumber ASC"; // Default sort
    }
    
    // Querys safely
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
    return false;
}

if (isset($_POST['delete'])) {
    $jobRef = !empty($_POST['Job_Reference_Number']) ? $_POST['Job_Reference_Number'] : null;
    $jobCode = !empty($_POST['Job_Code']) ? $_POST['Job_Code'] : null;

    if ($jobCode) {
        $jobRef = getJob($jobCode);
    }

    if ($jobRef && $jobRef !== 'UNKNOWN') {
        // Redirect to delete confirmation page with job reference
        header("Location: manage_delete_eoi.php?job_ref=" . urlencode($jobRef));
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage EOIs</title>
    <?php include './header.inc'; ?>
</head>
<body class="manage">
    <div class="logoContainer">
        <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    </div>

    <h1>Manage EOIs</h1>
    
    <?php 
    $activePage = 'manage';
    include './nav.inc';
    
    // Access control
    $require_login = true;
    include './redirect.inc';
    
    if (!$conn) {
        error_log("Database connection error: " . mysqli_connect_error());
        die('<p class="error">❌ Failed to connect to database! Contact administrator.</p>');
    }

    // Check if the form has been submitted for sorting
    if (isset($_POST['sort_first_name'])) { // Sort by First Name
        $filters['sort'] = 'First_Name ' . $_SESSION['sort_order']['First_Name'];
        // Toggle sort order
        $_SESSION['sort_order']['First_Name'] = ($_SESSION['sort_order']['First_Name'] === 'ASC') ? 'DESC' : 'ASC';
        $_SESSION['sort_state'] = [
            'First_Name' => ($_SESSION['sort_state']['First_Name'] + 1),
            'Last_Name' => 0,
            'Job_Reference_Number' => 0,
            'Status' => 0
        ];
        if ($_SESSION['sort_state']['First_Name'] == 3) {
            $_SESSION['sort_state']['First_Name'] = 0; // Reset state after 3 clicks
            unset($filters['sort']); // Reset sort if clicked 3 times
        }
    } elseif (isset($_POST['sort_last_name'])) { // Sort by Last Name
        $filters['sort'] = 'Last_Name ' . $_SESSION['sort_order']['Last_Name'];
        // Toggle sort order
        $_SESSION['sort_order']['Last_Name'] = ($_SESSION['sort_order']['Last_Name'] === 'ASC') ? 'DESC' : 'ASC';
        $_SESSION['sort_state'] = [
            'First_Name' => 0,
            'Last_Name' => ($_SESSION['sort_state']['Last_Name'] + 1),
            'Job_Reference_Number' => 0,
            'Status' => 0
        ];
        if ($_SESSION['sort_state']['Last_Name'] == 3) {
            $_SESSION['sort_state']['Last_Name'] = 0; // Reset state after 3 clicks
            unset($filters['sort']); // Reset sort if clicked 3 times
        }
    } elseif (isset($_POST['sort_job_title'])) { // Sort by Job Title
        $filters['sort'] = 'Job_Reference_Number ' . $_SESSION['sort_order']['Job_Reference_Number'];
        // Toggle sort order
        $_SESSION['sort_order']['Job_Reference_Number'] = ($_SESSION['sort_order']['Job_Reference_Number'] === 'ASC') ? 'DESC' : 'ASC';
        $_SESSION['sort_state'] = [
            'First_Name' => 0,
            'Last_Name' => 0,
            'Job_Reference_Number' => ($_SESSION['sort_state']['Job_Reference_Number'] + 1),
            'Status' => 0
        ];
        if ($_SESSION['sort_state']['Job_Reference_Number'] == 3) {
            $_SESSION['sort_state']['Job_Reference_Number'] = 0; // Reset state after 3 clicks
            unset($filters['sort']); // Reset sort if clicked 3 times
        }
    } elseif (isset($_POST['sort_status'])) { // Sort by Status
        $filters['sort'] = 'Status ' . $_SESSION['sort_order']['Status'];
        // Toggle sort order
        $_SESSION['sort_order']['Status'] = ($_SESSION['sort_order']['Status'] === 'ASC') ? 'DESC' : 'ASC';
        $_SESSION['sort_state'] = [
            'First_Name' => 0,
            'Last_Name' => 0,
            'Job_Reference_Number' => 0,
            'Status' => ($_SESSION['sort_state']['Status'] + 1)
        ];
        if ($_SESSION['sort_state']['Status'] == 3) {
            $_SESSION['sort_state']['Status'] = 0; // Reset state after 3 clicks
            unset($filters['sort']); // Reset sort if clicked 3 times
        }
    }
    ?>

    <div class="manage_container">
        <div class="manage_side_nav">
            
            <form method="POST" action="manage.php">
                <div class="form-group">
                    <label for="First_Name"><b>First Name</b></label>
                    <input type="text" id="First_Name" name="First_Name" 
                           value="<?= htmlspecialchars($filters['First_Name']) ?>">
                </div>

                <div class="form-group">
                    <label for="Last_Name"><b>Last Name</b></label>
                    <input type="text" id="Last_Name" name="Last_Name" 
                           value="<?= htmlspecialchars($filters['Last_Name']) ?>">
                </div>

                <hr>

                <div class="form-group">
                    <label for="Job_Reference_Number"><b>Job Position</b></label>

                    <select id="Job_Reference_Number" name="Job_Reference_Number">
                        <option value="">Select Position</option>
                        <option value="job-network-admin" <?= $filters['Job_Reference_Number'] === 'job-network-admin' ? 'selected' : '' ?>>Network Administrator</option>
                        <option value="job-software-developer" <?= $filters['Job_Reference_Number'] === 'job-software-developer' ? 'selected' : '' ?>>Software Developer</option>
                        <option value="job-cybersecurity-specialist" <?= $filters['Job_Reference_Number'] === 'job-cybersecurity-specialist' ? 'selected' : '' ?>>Cyber-Security Specialist</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Job_Code"><b>Reference Number</b></label>
                    <input type="text" id="Job_Code" name="Job_Code" 
                           value="<?= htmlspecialchars($filters['Job_Code']) ?>">
                </div>

                <div class="button-group">
                    <input type="submit" name="search" value="Search" class="submit">
                    <input type="submit" name="show_all" value="Show All" class="submit">
                    <input type="submit" name="delete" value="Delete All" class="delete"
                    <?= (!empty($_POST['Job_Reference_Number']) || !empty($_POST['Job_Code'])) ? '' : 'disabled' ?>>
                </div>
            </form>
            <div class="sort_nav">
        <form method="POST" action="manage.php">
            <hr>
            <label>Sort By
            <div class="form-group">
                <input type="submit" name="sort_first_name" 
                value="First Name<?= $_SESSION['sort_state']['First_Name'] === 1 ? ' ⬇️' : ($_SESSION['sort_state']['First_Name'] === 2 ? ' ⬆️' : '') ?>" class="submit_sort">
                <input type="submit" name="sort_last_name" 
                value="Last Name<?= $_SESSION['sort_state']['Last_Name'] === 1 ? ' ⬇️' : ($_SESSION['sort_state']['Last_Name'] === 2 ? ' ⬆️' : '') ?>" class="submit_sort">
                <input type="submit" name="sort_job_title" 
                value="Job Title<?= $_SESSION['sort_state']['Job_Reference_Number'] === 1 ? ' ⬇️' : ($_SESSION['sort_state']['Job_Reference_Number'] === 2 ? ' ⬆️' : '') ?>" class="submit_sort">
                <input type="submit" name="sort_status" 
                value="Status<?= $_SESSION['sort_state']['Status'] === 1 ? ' ⬇️' : ($_SESSION['sort_state']['Status'] === 2 ? ' ⬆️' : '') ?>" class="submit_sort"></label>
            </div>
        </form>
    </div>
        </div>
        
        <div class="manage_list">
            <?php
            $result = getEOIs($conn, $filters);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <fieldset class="eoi-entry">
                        <form method="post" action="manage_update_eoi.php">
                            <h2><?= htmlspecialchars($row['First_Name'] . " " . $row['Last_Name']) ?></h2>
                            <h3><?= htmlspecialchars(getJobTitle($row['Job_Reference_Number'])) ?> 
                                (<i><?= htmlspecialchars(getCode($row['Job_Reference_Number'])) ?></i>)
                            </h3>
                            
                            <div class="status-group">
                                <label><b>Status</b>
                                <select name="Status">
                                    <?php
                                    $statuses = ['New', 'In Progress', 'Finalised'];
                                    foreach ($statuses as $status) {
                                        echo "<option value='$status'" . ($row['Status'] === $status ? " selected" : "") . ">$status</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <input type="hidden" name="id" value="<?= $row['EOInumber'] ?>">
                            <input type="submit" name="update" value="Update" class="update"></label>
                        </form>
                    </fieldset>
                    <?php
                }
            } else {
                echo "<p>No results found 😢</p>";
            }
            ?>
        </div>
    </div>

    <?php include './footer.inc'; ?>
</body>
</html>