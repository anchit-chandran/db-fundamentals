<?php include_once("utilities.php")?>


<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2 justify-content-between">
    <div class="container">
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
    </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <ul class="navbar-nav align-middle">
            <li class="nav-item mx-1">
                <a class="nav-link" href="index.php">Browse</a>
            </li>
            <?php
            if (logged_in()) {
                include_once('./CONTENT_navbar_logged_in.php');
                // SUPERUSER
                if ($_SESSION['isSuperuser'] == True) {
                    echo '<li class="nav-item ml-3">
                        <a class="nav-link btn border-light" href="#">Admin</a>
                    </li>';
                }
            }
            ?>
        </ul>
    </div>
</nav>