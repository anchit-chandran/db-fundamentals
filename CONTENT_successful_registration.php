<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

header("refresh:5;url=login.php")
?>

<div class="row">
    <div class="col mt-5">
        <div class="alert alert-success" role="alert">
            <h2>👋 Thanks for signing up!</h2>
        </div>
        <p>Please check your email to activate your account.</p>
        <p>Click <a href="login.php">here</a> if you are not automatically redirected in 5 seconds.</p>
    </div>
</div>