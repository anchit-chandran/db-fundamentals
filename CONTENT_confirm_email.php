<?php include_once("CONTENT_header.php") ?>
<?php include_once 'database.php' ?>
<?php include_once("utilities.php") ?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $email = $_GET['email'];
    $valid_link = password_verify($email, $_GET['token']);

    if ($valid_link) {

        $query_set_user_active = "UPDATE User SET isActive=1 WHERE email='{$email}';";
        runQuery($query_set_user_active);

        echo '<div class="row">
        <div class="col mt-5">
            <div class="alert alert-success" role="alert">
                <h2>🔓 Thanks for confirming your email!</h2>
            </div>
            <p>You now have full access to the website.</p>
            <p>Click <a href="login.php">here</a> if you are not automatically redirected in 5 seconds.</p>
        </div>
    </div>';

        header("refresh:5;url=login.php");
    } else {
        echo '<div class="row">
        <div class="col mt-5">
            <div class="alert alert-danger" role="alert">
                <h2>🔓 Invalid Link</h2>
            </div>
            <p>Sorry, that link is invalid. Please check the link and try again.</p>
        </div>
    </div>';
    }
}
?>





