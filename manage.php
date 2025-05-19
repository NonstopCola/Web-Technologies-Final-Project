<!-- 5. HR manager queries (manage.php)
Create a web page manage.php that allows a manager to make the following queries of
the eoi table and returns a web page with the appropriate results.
✔ List all EOIs.
• List all EOIs for a particular position (given a job reference number).
✔ List all EOIs for a particular applicant given their first name, last name or both.
• Delete all EOIs with a specified job reference number
• Change the Status of an EOI. >
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
  <body>
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
<div class="apply_container">

<!-- SEARCH FORM -->
<fieldset>
  <form method="POST" action="manage.php">  

    <!-- First Name Label | First Name Input | Position Label -->
    <label for="firstname"><b>First Name</b></label>
    <input type="text" id="firstname" name="firstname" placeholder="First Name"></input>
    <label for="position"><b>Position:</b></label>

    <!-- Drop down job selection -->
    <select id="position" name="position"> 
          <option value="">Select Position</option>
          <option value="NetworkAdmin" name="Network Administrator">Network Administrator</option>
          <option value="SoftwareDev" name="Software Developer">Software Developer</option>
          <option value="CyberSpecialist" name="Cybersecurity Specialist">Cybersecurity Specialist</option>
    </select><br>

    <!-- Last Name Label | Last Name Text | Ref Number Label | Ref Number Input -->
    <label for="lastname"><b>Last Name</b></label>
    <input type="text" id="lastname" name="lastname" placeholder="Last Name"></input>
    <label for="refnumber"><b>Reference Number </b></label>
    <input type="text" id="refnumber" name="refnumber" placeholder="JK101"></input>

    <!-- New Line and submit button -->
    <br>
    <input type="submit" value="Search" class="submit">
  </form>
</fieldset>

<!-- SEARCH RESULT LOGIC -->
<?php
  // If this is the first time entering the page (without input), show whether we have connected to the server
  if (!$conn) {
    error_log("Database connection error: " . mysqli_connect_error());
    die("❌ Failed to connect to database! Contact administrator.");
  }
  else if($conn && empty($_POST)){
    echo "<p>✅ Successfuly connected to database, enter details above to view applications.</p>";
  }

  // Get each of the input values
  if($conn && !empty($_POST)){
    // trim (in case a user adds a space) 
    // ?? checks if $variable is filled? If not set variable to empty (which is the '')
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $refnumber = trim($_POST['refnumber'] ?? '');

    // Searches database for EOI which we chech each result for below
    // 1=1 allows us to just check AND in the if statements
    // without having to have WHERE and ELSE statements 
    $query = "SELECT * FROM eoi WHERE 1=1";
    if($firstname !== '') {
      $firstname_safe = mysqli_real_escape_string($conn, $firstname);
      $query .= " AND firstname LIKE '%$firstname_safe%'";
    }
    if($lastname !== '') {
      $lastname_safe = mysqli_real_escape_string($conn, $lastname);
      $query .= " AND lastname LIKE '%$lastname_safe%'";
    }
    if($position !== '') {
      $position_safe = mysqli_real_escape_string($conn, $position);
      $query .= " AND position LIKE '%$position_safe%'";
    }
    if($refnumber !== '') {
      $refnumber_safe = mysqli_real_escape_string($conn, $refnumber);
      $query .= " AND refnumber LIKE '%$refnumber_safe%'";
    }

    // Get results from data using query
    $result = mysqli_query($conn, $query);

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
      $status = $row['status'];
      // Create fieldset for a row of EOI
      echo "<fieldset>";
      // Show first and last name
      echo "<label><b>Name: </b></label>";
      echo "<label>" . $row['firstname'] . " " . $row['lastname'] . "</label><br>";
      echo "<label><b>Position: </b></label>";
      echo "<label>" . $row['position'] . " - <i>" . $row['refnumber'] . "</i></label><br>";
      // Create a form, this will be used to update the status of an EOI
      echo "<form method='post' action='manage_update_eoi.php'>";
      // Create status label and drop down
      echo "<label><b>Status: </b></label>";
      echo "<select name='status'>";
      // If the variable $status equals the same as the value of the dropdown (Active, Review, Status)
      // Then have it as 'SELECTED', otherwise leave it blank
      echo "<option value='Active'" . ($status == 'Active' ? " selected" : "") . ">Active</option>";
      echo "<option value='Review'" . ($status == 'Review' ? " selected" : "") . ">Review</option>";
      echo "<option value='Expired'" . ($status == 'Expired' ? " selected" : "") . ">Expired</option>";
      echo "</select><br>";
      // Create hidden variable that links to the row ID which we use to update the status in the database
      echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
      echo "<input type='submit' name='update' value='Update' class='update'>";
      echo "</form>";
      // Create a new form, this sends a user to a new prompt page before they delete a listing
      // Also have a hidden variable for the row id
      // Create a delete button that submits the form
      echo "<form action='manage_delete_eoi.php' method='post'>";
      echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
      echo "<input type='submit' value='🗑 Delete' class='delete'>";
      echo "</form>";
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