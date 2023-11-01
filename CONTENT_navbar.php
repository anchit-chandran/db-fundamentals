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
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

                echo '<a class="nav-link" href="logout.php">Logout</a>';
            } else {
                echo "<a class='nav-link' href='CONTENT_sign_in.php'>Login</a>";
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
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
            echo ('
                <li class="nav-item mx-1">
                  <a class="nav-link" href="mybids.php">My Bids</a>
                </li>
                <li class="nav-item mx-1">
                  <a class="nav-link" href="recommendations.php">Recommended</a>
                </li>');
        }
        if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
            echo ('
                <li class="nav-item mx-1">
                  <a class="nav-link" href="mylistings.php">My Listings</a>
                </li>
                <li class="nav-item ml-3">
                  <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
                </li>');
        }
        ?>
    </ul>
</nav>