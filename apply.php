<!-- TO DO
 + Add other job roles
 + Add more placeholders
 + Add reference numbers to job roles
 + Validate html
 + Check Assignment requirements
-->

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            // Calls for the start of the session and includes the header
            session_start();
            include './header.inc'
        ?>
        <!-- title -->
        <title>Apply</title>
    </head>

    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo" width="100" height="100">
    <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <!-- page heading-->
        <h1>Apply to JKTN</h1>

        <?php
            $activePage = 'apply';
            // Set the active page for navigation highlighting
            include './nav.inc';
        ?>

        <div class="apply_container">
        <!-- paragraph description before form-->
        <p>See a role you like? Submit an application and we'll get in contact with your shortly!</p>

        <!--create form, data top be sent to process_eoi file to add records to table-->
        <form action="./process_eoi.php" method="post" novalidate="novalidate">
            <!--job reference-->
            <fieldset>
                <legend>Job Reference</legend>
                <p><label for="job-ref">Job Reference</label>
                    <select name="job-ref" id="job-ref" required>
                        <!-- change list of jobs here, be sure to keep first value as empty -->
                        <option value="">Please Select</option>
                        <option value="job-network-admin">Network Administrator - K9986</option>
                        <option value="job-software-developer">Software Developer - J7652</option>
                        <option value="job-cybersecurity-specialist">Cybersecurity Specialist - S9475</option>
                    </select>
                </p>
            </fieldset>

            <!--personal details-->
            <fieldset>
                <legend>Personal Details</legend>
                <!-- first name
                ONLY : Letters (a-zA-Z) and letters no longer than 20 {20}
                -->
                <p><label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" pattern="[a-zA-Z]{1,20}"
                    oninvalid="this.SetCustomValidity('Ensure name only contains alphabetic letters, NOT Symbols or Numbers')" 
                    oninput="this.SetCustomValidity('')" required>
                </p>
                <!-- last name
                ONLY : Letters 
                -->
                <p><label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" pattern="[a-zA-Z]{1, 20}"
                    oninvalid="this.SetCustomValidity('Ensure name only contains alphabetic letters, NOT Symbols or Numbers')"
                    oninput="this.SetCustomValidity('')" required>
                </p>

                <!-- DOB 
                ONLY : Dates from years 1900 to 2025, with dd being between 01-31, mm being 01-12, yy being 1950-2007.
                Pattern referenced from Copilot Ai with prompt: "what do I need to add to this pattern so that the first d cannot be above 31, 
                the second m above 12 and the third y has to be between 1950 to 2007" 
                -->

                <p><label for="date-of-birth">Date of Birth</label>
                    <input type="date" name = "date-of-birth" id="date-of-birth" value="2000-01-01" min="1950-01-01" max="2007-01-01" required="required">
                </p>
            </fieldset>

                <!-- Gender
                Radio selection of Male, Female and Other 
                -->
                <fieldset>
                    <legend>Gender</legend>
                <p><label for="male">Male</label>
                    <input type="radio" name="gender" id="male" value="male" checked="checked">
                    <label for="female">Female</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="other">Other</label>
                    <input type="radio" name="gender" id="other" value="other">
                </p>
            </fieldset>

            <!--address-->
            <fieldset>
                <legend>Address</legend>
                <!-- street
                ONLY : Letters and numbers, from 1-40 characters
                -->
                <p><label id="street">Street Address
                    <input type="text" name="streetAddress" pattern="[a-zA-Z0-9]{1, 40}"
                    oninvalid="this.setCustomValidity('Max 40 characters')"
                    oninput="this.setCustomValidity('')" required></label>
                </p>

                <!-- suburb
                ONLY : Letters from 1 - 40 characters
                -->
                <p><label id="suburb">Suburb / Town
                    <input type="text" name="suburb" pattern="[a-zA-Z]{1, 40}"
                    oninvalid="this.setCustomValidity('Max 40 characters')"
                    oninput="this.setCustomValidity('')" required></label>
                </p>

                <!-- states
                drop down menu for each state + empty 'please select state'
                -->
                <p><label for="state">State</label>
                    <select id="state" name="state">
                        <option value="">Please Select</option>
                        <option value="VIC">VIC</option>
                        <option value="NSW">NSW</option>
                        <option value="QLD">QLD</option>
                        <option value="NT">NT</option>
                        <option value="WA">WA</option>
                        <option value="SA">SA</option>
                        <option value="TAS">TAS</option>
                        <option value="ACT">ACT</option>
                    </select>
                </p>

                <!-- post-code
                ONLY : numbers ranging from 0200 - 9944 (Australian Post-Code range)
                \d - only digit
                {4} - cant only be digit of 4
                
                -->
                <p><label for="post-code">Post Code</label>
                    <input type="text" name="post-code" id="post-code"
                    pattern="0[2-9][0-9]{2}|[1-8][0-9]{3}|9[1-8][0-9]{2}|99[0-3][0-9]|994[0-4]"
                    oninvalid="this.setCustomValidity('Please enter a 4-digit number between 0200-9944')"
                    oninput="this.setCustomValidity('')" required>
                </p>

            </fieldset>

            <!--contact details
            Used Pattern from module 3
            Self-explaination
            [A-Za-z0-9._%+-] - allow a to z, 0 to 9 as well as . _ % + - symbols
            +@ plus an @ symbol
            +\. include a .
            [A-Za-z]{2,} include letters a to Z with at least 2 characters

            -->
            <fieldset>
                <legend>Contact Details</legend>
                <!-- email, requires @ and .com -->
                <!-- I was told NOT to use HTML5s email validation.
                Upon testing this regex - it is not working. Can't do much 
                due to the restrictions of this assignment :( 
                -->
                <p><label id="contact-details">Email
                    <input type="text" name="contact-email" id="contact-email" placeholder="john@gmail.com"
                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                    oninvalid="this.SetCustomValidity('Please enter a valid email!')"
                    oninput="this.setCustomValidity('')" required></label>   
                </p>
                <!--
                phone numner : minimun length 8 - max 12
                
                PATTERN EXPLAINATINO
                [0-9 ] : only digits ranging from 0-9 and spaces
                {8,12} : limit between 8-12 characters
                -->
                <p><label for="phone-number">Phone Number</label>
                    <input type="text" name="phone-number" id="phone-number" pattern="[0-9 ]{8,12}"
                    oninvalid="this.setCustomValidity('Please enter a valid phone number!')"
                    oninput="this.setCustomValidity('')" required>
                </p>
            </fieldset> 

            <!--required skills-->
            <fieldset>
                <legend>Required Skills</legend>
                <p><label for="windows-server">Windows Server</label>
                    <input type="checkbox" id="windows-server" name="category[]" value="windows-server" checked="checked">

                    <label for="cloudfare-zero">Cloudfare Zero</label>
                    <input type="checkbox" id="cloudfare-zero" name="category[]" value="cloudfare-zero">

                    <label for="python">Python</label>
                    <input type="checkbox" id="python" name="category[]" value="python">

                    <label for="powershell">PowerShell</label>
                    <input type="checkbox" id="powershell" name="category[]" value="powershell">
                </p>
                <p><label id="other-skills">Other Skills<br>
                <textarea id="textarea" name="textarea" rows="5"cols="50"
                placeholder="Let us know other skills you have that may be important!"></textarea></label></p>
            </fieldset>

            <!-- submit buttons-->
            <input type="submit" value="Apply" class="submit">
        </form>
        </div>

        <?php
            include './footer.inc';
        ?>
    </body>
</html>