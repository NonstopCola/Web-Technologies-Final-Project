<!-- 5. HR manager queries (manage.php)
Create a web page manage.php that allows a manager to make the following queries of
the eoi table and returns a web page with the appropriate results.
✔ List all EOIs.
• List all EOIs for a particular position (given a job reference number).
✔ List all EOIs for a particular applicant given their first name, last name or both.
• Delete all EOIs with a specified job reference number
• Change the Status of an EOI. >
-->
<?php
session_start();
require_once("settings.php");

//$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

include './header.inc'

?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>HR Manager</title>
  </head>
  <body>
    <div class="logoContainer">
         <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    </div>

    <h1>HR Manager</h1>
    <?php include './nav.inc'; ?>
        <div class="apply_container">

<!-- SEARCH FORM -->
<fieldset>
  <form method="GET" action="manage.php">  
    <label for="firstname"><b>First Name</b></label>
    <input type="text" id="firstname" name="firstname" placeholder="First Name"></input>
    <label for="position"><b>Position:</b></label>
    <select id="position" name="position"> 
          <option value="">Select Position</option>
          <option value="NetworkAdmin" name="Network Administrator">Network Administrator</option>
          <option value="SoftwareDev" name="Software Developer">Software Developer</option>
          <option value="CyberSpecialist" name="Cybersecurity Specialist">Cybersecurity Specialist</option>
    </select><br>
  <label for="lastname"><b>Last Name</b></label>
    <input type="text" id="lastname" name="lastname" placeholder="Last Name"></input>
    <label for="refnumber"><b>Reference Number </b></label>
    <input type="text" id="refnumber" name="refnumber" placeholder="JK101"></input>
    <br>
    <input type="submit" value="Search" class="submit">
  </form>
</fieldset>
  <?php

    // If this is the first time entering the page (without input), show whether we have connected to the server
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        echo "<p>❌ Failed to connect to database! Contact administrator.</p>";
    }
    else if($conn && empty($_GET)){
        echo "<p>✅ Successfuly connected to database, enter details above to view applications.</p>";
    }

    // Get each of the input values
    if($conn && !empty($_GET)){
      $firstname = trim($_GET['firstname'] ?? '');
      $lastname = trim($_GET['lastname'] ?? '');
      $position = trim($_GET['position'] ?? '');
      $refnumber = trim($_GET['refnumber'] ?? '');

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

  // save the result
  $result = mysqli_query($conn, $query);


  // show result in fieldset
  if($result && mysqli_num_rows($result) > 0){

    while ($row = mysqli_fetch_assoc($result)) {
    // TODO:
    // 1. Add functionality to update button so drop-down selection is added to databse
    // 2. Make sure you can search by each single input (without the other)
    // 3. Realign update and delete button
    // 4. Check security of input - determine what is best with GET and POST and whether we can hide more information with the search results?
    // 5. Go to delete_prompt - beautify it and add more functionality (like going back to the search results)


    echo "<fieldset>";

    // ↓↓↓ Fix: Set the current status
    $status = $row['status'];

    echo "<label><b>Name: </b></label>";
    echo "<label>" . $row['firstname'] . " " . $row['lastname'] . "</label><br>";
    echo "<label><b>Position: </b></label>";
    echo "<label>" . $row['position'] . " - <i>" . $row['refnumber'] . "</i></label><br>";

    // Form and dropdown
    echo "<form method='post' action='manage.php'>"; // also fixed typo: missing closing quote
    echo "<label><b>Status: </b></label>";
    echo "<select name='status'>";
    echo "<option value='Active'" . ($status == 'Active' ? " selected" : "") . ">Active</option>";
    echo "<option value='Review'" . ($status == 'Review' ? " selected" : "") . ">Review</option>";
    echo "<option value='Expired'" . ($status == 'Expired' ? " selected" : "") . ">Expired</option>";
    echo "</select><br>";

    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
    echo "<input type='submit' name='update' value='Update' class='update'>";
    echo "</form>";

    echo "<form action='delete_prompt.php' method='post'>";
    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
    echo "<input type='submit' value='🗑 Delete' class='delete'>";
    echo "</form>";

    echo "</fieldset>";
}

  }
  else {
    echo "<p>No results found 😢</p>";
  }
}
    /*
<!--        LOGIN FORM
        <form method="post" action="">
            <fieldset>
                <form method="POST" action="">
                    <label for="username"><b>Username</b></label><br>
                    <input type="text" placeholder="Enter Username" name="username" required><br><br>
                    <label for="psw"><b>Password</b></label><br>
                    <input type="password" placeholder="Enter Password" name="psw" required><br><br>
                    <label for="checkbox">Remember me</label>   
                    <input type="checkbox" id="windows-server" name="category[]" value="windows-server" checked="checked">
                    <p></p>
                    <input type="submit" value="Login" class="submit">
                    <a href="forgot.php" class="forgot-password">Forgot Password</a>
                </form>
            </fieldset>
        </form>
-->

    </div>
  </body>
  </html>
  */