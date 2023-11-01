<?php
function logged_in()
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
        return true;
    } else {
        return false;
    }
}
?>


<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2 justify-content-between">
    <a class="navbar-brand" href="index.php">Db-friends</a>
    <ul class="navbar-nav ml-auto">
        <li class='nav-item'>
            <?php
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                echo '<a class="nav-link" href="profile.php">Hey, ANCHIT 👋</a>';
            }
            ?>
        </li>
        <li class="nav-item">

            <?php
            // Displays either login or logout on the right, depending on user's
            // current status (session).
            if (logged_in()) {

                echo '<a class="nav-link" href="logout.php">Logout</a>';
            } else {
                echo "
                
                <li class='nav-item mx-1'>
                    <a class='nav-link' href='login.php'>Login</a>
                </li>
                <li class='nav-item mx-1'>
                    <a class='nav-link' href='register.php'>Register</a>
                </li>
                ";
            }
            ?>

        </li>

    </ul>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <ul class="navbar-nav align-middle">
        <li class="nav-item mx-1">
            <a class="nav-link" href="index.php">Browse</a>
        </li>
        <?php
            if (logged_in()) {
                include_once('./CONTENT_navbar_logged_in.php');
            }
        ?>
        
    </ul>
</nav>