<?php
include_once('setup.php');
include_once("utilities.php");
include_once("config.php");
if ($EMAIL_SENDING) {
    include('SCHEDULED_handle_auctions_ended.php');
}
date_default_timezone_set('Europe/London');


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

<?php include_once('scripts.php') ?>

</html>