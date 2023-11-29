<?php
include_once("../database.php");
include_once("../utilities.php");
date_default_timezone_set('Europe/London');
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST["email"])) {
    $email = $_POST["email"];

    $email_exists = runQuery("SELECT email FROM User WHERE email='{$email}';");

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($email_exists->num_rows > 0) {
            echo "<p class='form_error'>{$email} is already taken!";
        } else {
            echo "<p class='form_valid'><span class='fw-bold'>{$email}</span> is available";
        }
    }
}
?>
