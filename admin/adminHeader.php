<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<script>
    function logoutFunction() {
        // Confirm logout
        if (confirm('Are you sure you want to log out?')) {
            // Redirect to logout.php
            window.location.href = '../logout.php';
        }
    }
</script>
<!-- nav -->

<nav class="navv">
    <a href="#" id="logo"><img src="assets/logo 2.png" class="logo2"></a>
    <ul>
        <li><a href="http://localhost/CeylonHappens/index.php">home</a></li>
        <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
        <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
        <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
        <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
        <li>
            <?php
            if (isset($_SESSION['user_id'])) {
                ?>
                <button type="button" onclick="logoutFunction()" style="text-decoration:none;"
                    class="btn btn-outline-light mt-3 ml-6">
                    Log Out
                </button>
                <?php
            } else {
                ?>

                <a href="http://localhost/CeylonHappens/login/index.php" class="btn">Loging In</a>
                </a>
                <?php
            } ?>
        </li>
    </ul>
</nav>



</nav>