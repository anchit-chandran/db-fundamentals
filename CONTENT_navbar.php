<?php include_once("utilities.php") ?>



<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2 justify-content-between">
    <div class="container">
        <a class="navbar-brand" href="index.php">Db-friends</a>
        <ul class="navbar-nav ml-auto">

            <li class='nav-item'>
                <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    $name = runQuery("SELECT firstName FROM User WHERE userId={$_SESSION['userId']}")->fetch_assoc()['firstName'];
                    echo "<a class='nav-link' href='myprofile.php'>Hey, {$name} 👋</a>";
                    $is_superuser = runQuery("SELECT isSuperuser FROM User WHERE userId={$_SESSION['userId']}")->fetch_assoc()['isSuperuser'];

                    if ($is_superuser) {
                        echo "<li class='nav-item'>
                                <a class='nav-link' href='admin.php'>Admin</a>
                            </li>";
                    }
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
        <ul class="navbar-nav align-middle nav-underline">
            <li class="nav-item mx-1">
                <a class="nav-link <?php return_active_if_current_nav($page='browse'); ?>" href="index.php">Browse</a>
            </li>
            <?php
            if (logged_in()) {
                include_once('./CONTENT_navbar_logged_in.php');
                
            }
            ?>
        </ul>
    </div>
</nav>