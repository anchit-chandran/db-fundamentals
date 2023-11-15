<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password_raw = $_POST['password'];

    // Dont use all cols so could clean up if time
    $userFound = runQuery("SELECT *, password FROM User WHERE email='{$email}'");

    if ($userFound) {
        while ($row = $userFound->fetch_assoc()) {

            // LOG IN USER
            if (password_verify($password_raw, $row['password'])) {

                if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['userId'] = $row['userId'];
                $_SESSION['isSuperuser'] = $row['isSuperuser'];
                $_SESSION['logged_in'] = True;

                header("Location:index.php");
            }
        };
    };

    // FAILED LOGIN
    $failed_login = 'Please enter a correct email address and password. Note that both fields may be case-sensitive.';
};
?>

<div class="row justify-content-center signin_row">
    <div class="signin_form_col col-6">
        <h2 class='pb-2 border-bottom'>Sign in</h2>
        <form method="POST" action='#'>
            <div id="errorDiv" class="mt-2 text-danger">
                <?php if (isset($failed_login)) {
                    echo $failed_login;
                } ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name='email' type="email" class="form-control" id="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name='password' id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a class="btn btn-secondary" href='register.php'>Create account</a>
            <a href="forgot_password.php" class="btn btn-secondary">Reset password</a>
        </form>
    </div>
</div>