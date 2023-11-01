<?php
session_start();
?>


<!doctype html>
<html lang="en">

<head>
    <?php include('header.php') ?>
</head>

<body>

    <!-- Navbars -->
    <?php include('navbar.php') ?>

    <main class='container'>
        <?php
        if (isset($content)) {
            include($content);
        }
        ?>
    </main>

    <!-- FOOTER -->
    <footer>
        <?php include('footer.php') ?>
    </footer>

</body>

</html>