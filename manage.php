<!-- 5. HR manager queries (manage.php)
Create a web page manage.php that allows a manager to make the following queries of
the eoi table and returns a web page with the appropriate results.
FIX:
- Getting a position full-name
- Getting a position code
TODO:
✔ List all EOIs.
• List all EOIs for a particular position (given a job reference number).
✔ List all EOIs for a particular applicant given their first name, last name or both.
• Delete all EOIs with a specified job reference number
✔ Change the Status of an EOI. >
-->

  <!DOCTYPE html>
  <html lang="en">
  <head>
  <?php 
    // Starts the session and includes the header
    session_start();
    require_once("settings.php");
    include './header.inc';
    ?>
  <title>Manage</title>
  </head>
  <body class="manage">
    <div class="logoContainer">
      <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    </div>

    <h1>Manage</h1>
    <?php 

    // Sets the active page for navigation highlighting
    $activePage = 'manage';
    include './nav.inc'; 

    // Doesn't allow anyone to access this page unless they are logged in
    $require_login = true;
    include './redirect.inc';
    ?>
    
<!-- WIP div container for page -->
<form method="POST" action="manage.php" class="manage_container">
  <div class="manage_side_nav">
      <label type="search" for="firstname"><b>First Name</b></label>
      <br>
      <input type="text" id="First_Name" name="First_Name">
      <br>
      <label type="search" for="lastname"><b>Last Name</b></label>
      <br>
      <input type="text" id="Last_Name" name="Last_Name">
      <br>
      <label type="search" for="job_dropdown"><b>Job Position</b></label>
      <br>
      <select id="position" name="position"> 
        <option value="">Select Position</option>
        <option value="NetworkAdmin">Network Administrator</option>
        <option value="SoftwareDev">Software Developer</option>
        <option value="CyberSpecialist">Cybersecurity Specialist</option>
      </select>
      <br>
      <label type="search" for="Job_Reference_Number"><b>Reference Number</b></label>
      <br>
      <input type="text" id="Job_Reference_Number" name="Job_Reference_Number">
      <br>
      <input type="submit" value="Search" class="submit">
</div>
  <div class="manage_list">
  <?php
  $refnumberEntered = !empty($_POST['refnumber']) ||!empty($_POST['position']);
        if($refnumberEntered){
        echo "<form action='manage_delete_eoi.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $row['EOInumber'] . "'>";
        echo "<input type='submit' value='🗑 Delete' class='delete'>";
        echo "</form>";
      }
      ?>
</fieldset>

<!-- SEARCH RESULT LOGIC -->
<?php
  // If this is the first time entering the page (without input), show whether we have connected to the server
  if (!$conn) {
    error_log("Database connection error: " . mysqli_connect_error());
    die("❌ Failed to connect to database! Contact administrator.");
  }
  else{
    // Create filter from inputs and sanitize
    $filters = [
      'First_Name' => trim($_POST['First_Name'] ?? ''),
      'Last_Name' => trim($_POST['Last_Name'] ?? ''),
      'Job_Reference_Number' => trim($_POST['Job_Reference_Number'] ?? ''),
      'refnumber' => trim($_POST['refnumber'] ?? ''),
      'Status' => trim($_POST['Status'] ?? ''),
    ];

  $result = getEOIs($conn, array_filter($filters));

    // show result in fieldset (if we found something)
    if($result && mysqli_num_rows($result) > 0){

      // Show each row of the results
      while ($row = mysqli_fetch_assoc($result)) {
      // TODO:
      // 1. Add functionality to update button so drop-down selection is added to databse
      // 2. Make sure you can search by each single input (without the other)
      // 3. Realign update and delete button
      // 4. Check security of input - determine what is best with GET and POST and whether we can hide more information with the search results?
      // 5. Go to delete_prompt - beautify it and add more functionality (like going back to the search results)

      // Set the status variable (so we can change it later)
      //$status = $row['status'];
      // Create fieldset for a row of EOI
      echo "<fieldset>";
      // Create a form, this will be used to update the status of an EOI
      echo "<form method='post' action='manage_update_eoi.php'>";
      // Show first and last name
      echo "<label><b>Name: </b></label>";
      echo "<label>" . $row['First_Name'] . " " . $row['Last_Name'] . "</label><br>";
      echo "<label><b>Position: </b></label>";
      echo "<label>" . $row['Job_Reference_Number'] . " - <i>" . $row['Job_Reference_Number'] . "</i></label><br>";
      // Create status label and drop down
      echo "<label><b>Status: </b></label>";
      echo "<select name='Status'>";
      // If the variable $status equals the same as the value of the dropdown (Active, Review, Status)
      // Then have it as 'SELECTED', otherwise leave it blank
      echo "<option value='New'" . ($status == 'New' ? " selected" : "") . ">New</option>";
      echo "<option value='Current'" . ($status == 'Current' ? " selected" : "") . ">Current</option>";
      echo "<option value='Final'" . ($status == 'Final' ? " selected" : "") . ">Final</option>";
      echo "</select><br>";
      // Create hidden variable that links to the row ID which we use to update the status in the database
      echo "<input type='hidden' name='id' value='" . $row['EOInumber'] . "'>";
      echo "<input type='submit' name='update' value='Update' class='update'>";
      echo "</form>";
      // Create a new form, this sends a user to a new prompt page before they delete a listing
      // Also have a hidden variable for the row id
      // Create a delete button that submits the form
      
      echo "</fieldset>";
    }
  }
  // If the database didn't return any results
  else {
    echo "<p>No results found 😢</p>";
  }
}
?>
    </div>
    <?php include './footer.inc'; ?>
  </body>
</html>

<?php
// Extra functions and use cases
function getEOIs($conn, $filters=[]){
  $query = "SELECT * FROM eoi WHERE 1=1";

  if(!empty($filters["First_Name"])){
    $firstname = mysqli_real_escape_string($conn, $filters["First_Name"]);
    $query .= " AND First_Name LIKE '%firstname%'";
  }
  if(!empty($filters["Last_Name"])){
    $firstname = mysqli_real_escape_string($conn, $filters["Last_Name"]);
    $query .= " AND Last_Name LIKE '%lastname%'";
  }
  if(!empty($filters["Job_Reference_Number"])){
    $firstname = mysqli_real_escape_string($conn, $filters["Job_Reference_Number"]);
    $query .= " AND Job_Reference_Number AND '%jobref%'";
  }
  if(!empty($filters["refnumber"])){
    $firstname = mysqli_real_escape_string($conn, $filters["refnumber"]);
    $query .= " AND refnumber LIKE '%refnumr%'";
  }

  return mysqli_query($conn, $query);
}
?>