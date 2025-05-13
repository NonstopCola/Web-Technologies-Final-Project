<ul id="nav">
    <!-- Used chatgpt to create the class command -->
    <li><a class="<?= ($activePage == 'index') ? 'active' : '' ?>" href="./index.php">Home</a></li>
    <li><a class="<?= ($activePage == 'about') ? 'active' : '' ?>" href="./about.php">About</a></li>
    <li><a class="<?= ($activePage == 'jobs') ? 'active' : '' ?>" href="./jobs.php">Jobs</a></li>
    <li><a class="<?= ($activePage == 'apply') ? 'active' : '' ?>" href="./apply.php">Apply</a></li>
    <?php if (isset($_SESSION['username'])): ?>
        <li><a class="<?= ($activePage == 'manage') ? 'active' : '' ?>" href="./manage.php">Manage</a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['username'])): ?>
        <li><a class="<?= ($activePage == 'enhancements') ? 'active' : '' ?>" href="./enhancements.php">Enhancements</a></li>
    <?php endif; ?>
    <li class="mail"><a href="mailto:jktngroup4@gmail.com"><span>info@jktn.com.au</span></a></li>
    <?php if (isset($_SESSION['username'])): ?>
        <li class="login"><a class="<?= ($activePage == 'login') ? 'active' : '' ?>" href="./login.php">Logout</a></li>
    <?php else: ?>
        <li class="login"><a class="<?= ($activePage == 'login') ? 'active' : '' ?>" href="./login.php">Login</a></li>
    <?php endif; ?>
    <?php if (isset($_SESSION['username'])): ?>
        <li class="registrations"><a class="<?= ($activePage == 'registrations') ? 'active' : '' ?>" href="./registrations.php">Registrations</a></li>
    <?php endif; ?>
</ul>