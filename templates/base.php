<?php
include_once('setup.php');
include_once("utilities.php");
?>


<!doctype html>
<html lang="en">

<head>
    <?php include('CONTENT_header.php') ?>
    <title>
        <?php
        if (isset($title)) {
            echo $title;
        } else {
            echo '⏰NO TITLE';
        }
        ?>
    </title>
</head>

<body>

    <!-- Navbars -->
    <?php include('CONTENT_navbar.php') ?>

    <main class='container h-100'>
        <?php
        if (isset($_SESSION['flash'])) {
            include('CONTENT_flash.php');
        };

        if (isset($content)) {
            if ((isset($_SESSION['userId'])) && (!check_user_active($_SESSION['userId']))) {
                $content = 'CONTENT_account_not_active.php';
            };

            include($content);
        }
        ?>
    </main>

    <!-- FOOTER -->
    <footer>
        <?php include('CONTENT_footer.php') ?>
    </footer>

</body>

</html>