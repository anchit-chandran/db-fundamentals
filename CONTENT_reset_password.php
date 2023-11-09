<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>

<?php

$pw_update_success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);

    $query_update_user_pw = "UPDATE User SET password='$password' WHERE email='$email';";
    runQuery($query_update_user_pw);
    $pw_update_success_message = '<div class="alert alert-success" role="alert">
    <h2>Successfully updated your password!</h2>
</div>';
    header('refresh:5;url=login.php');
}
?>

<div class="row justify-content-center signin_row">
    <div class="signin_form_col col-6">

        <?php echo $pw_update_success_message ?>

        <h2 class='pb-2 border-bottom'>Reset Password</h2>
        <form method="POST" action='#'>



            <div class="mb-3">
                <label for="inputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name='password1' id="inputPassword1">
            </div>
            <div class="mb-3">
                <label for="inputPassword2" class="form-label">Repeat password</label>
                <input type="password" class="form-control" name='password2' id="inputPassword2" aria-describedby="pw2Help">
                <div id="pw2Help" class="form-text">Please type your password again.</div>
            </div>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                $email = $_GET['email'];
            }
            echo "<input type='hidden' name='email' value='$email'>"
            ?>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>