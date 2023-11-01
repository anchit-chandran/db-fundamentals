<?php
session_start();
$base_url = 'https://localhost/db-fundamentals';
?>


<!doctype html>
<html lang="en">

<head>
    <?php include('CONTENT_header.php') ?>
</head>

<body>

    <!-- Navbars -->
    <?php include('CONTENT_navbar.php') ?>

    <main class='container h-100'>
        <?php
        if (isset($content)) {
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