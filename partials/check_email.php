<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_POST["email"])) {
        $email = $_POST["email"];
        echo "<div class='form_error'>{$email}</div>";
    }
?>
