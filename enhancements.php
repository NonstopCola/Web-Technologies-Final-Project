<!DOCTYPE html>
<html lang="en">
    <head>
        <?php 
            // Starts the session and includes the header
            session_start();
            include './header.inc';
        ?>
        <title>Enhancements Page</title>
    </head>
    <body>
        <div class="logoContainer">
            <img id="Logo" src="./images/final_logo.png" alt="JKTN Logo">
    <!--company logo created through chatgpt.com using the prompt "Create a logo for a tech company called JKTN using a simplistic design and the base colour blue" -->
        </div>
        <h1>Enhancements Page</h1>
        <?php
            // Sets the active page for navigation highlighting
            $activePage = 'enhancements';
            include './nav.inc';

            // Redirects to error page if an issue arises, sets it so a login is required
            $require_login = true;
            include './redirect.inc';
        ?>
        <h2 class='setMiddle'>Enhancements</h2>
        <p class='setMiddle'>These are the listed enhancements that have been made to the system for it's improvement.</p>
        <section id="manageEnhancement">
            <h3 class='setMiddle'>Management</h3>
            <dl>
                <dt>Access</dt>
                <dd>Only users that are logged in can access the management page, requiring a unique username and password to create an account, with validation required for it to be activated, this can be done through the registrations page that is avaliable to allow validated accounts.</dd>
                <dt>Sorting</dt>
                <dd>The EOI table may be sorted through individual fields based on the column header, allowing easier management of the EOI data and a better experience in searching through the applicances for a qualified candidate.</dd>
                <dt>Searching</dt>
                <dd>Specific EOI's may be searched for through the search bar, allowing inputs of the first name, last name, job title and reference number.</dd>
        </section>
        <section id="accountsEnhancement">
            <h3 class='setMiddle'>Accounts</h3>
            <dl>
                <dt>Account Creation</dt>
                <dd>Users may create an account through the register page, this requires a username and password, with the password being hashed for security - additionally the data inputted is validated server side to ensure it is valid.</dd>
                <dt>Account Validation</dt>
                <dd>Users may be validated through the registrations page, this allows for a better experience in managing the accounts and ensuring that only valid users are able to access the system.</dd>
                <dt>Login</dt>
                <dd>Users may log in to the system through the login page, this requires a username and password, with the password in the database being verified through a secure system, allowing it for it remain hashed otherwise.</dd>
                <dt>Timeout</dt>
                <dd>If a user is unable to log in after 3 attempts, they will be blocked for 60 seconds, this is to prevent brute force attacks and ensure that the system is secure.</dd>
                <dt>Logout</dt>
                <dd>Users may log out of the system, this will clear the session and ensure that the user is logged out of the system, this is to prevent unauthorised access to the system.</dd>
            </dl>
        </section>
        <section id='securityEnhancement'>
            <h3 class='setMiddle'>Security</h3>
            <dl>
                <dt>SQL Injection Prevention</dt>
                <dd>Prepared statements are used to prevent SQL injection attacks, this is to ensure that the system is secure and that no unauthorised access can be gained through SQL injection attacks.</dd>
                <dt>Server-Side Validation</dt>
                <dd>Server-side validation is used on the data inputs given by the user, this is to ensure that the data is valid and that no unauthorised access can be gained through invalid data inputs.</dd>
                <dt>Client-Side Validation</dt>
                <dd>This is done in addition to the server-side validation, this is to ensure that the data is valid and that no unauthorised access can be gained through invalid data inputs.</dd>
                <dt>Session Management</dt>
                <dd>A new session token is generated each time a user logs in, this is to ensure that the session is secure as no unauthorised person(s) can use the expired session tokens to gain access to a validated account.</dd>
        </section>
        <section id='errorEnhancement'>
            <h3 class='setMiddle'>Error Handling</h3>
            <dl>
                <dt>Error Page</dt>
                <dd>Errors are handled through the error page, this is to ensure that the user is aware of the error and that they are able to rectify it, this is to ensure that the system is user friendly and that the user is able to use the system without any issues.</dd>
                <dt>Error Messages</dt>
                <dd>When an issue occurs when accessing the database or through other parts of the system, an error message is displayed to the user with details on the cause of the error.</dd>
            </dl>
        </section>
        <?php
            // Sets up the footer
            include './footer.inc';
        ?>
    </body>
</html>