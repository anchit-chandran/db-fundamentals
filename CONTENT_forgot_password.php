<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>

<?php

$email_error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $to = $email;
        $subject = "Reset Password";
        $token = password_hash($email, PASSWORD_DEFAULT);
        $confirmation_link = "http://localhost/db-fundamentals/reset_password.php?token={$token}&email={$email}";
        $message = "Hi,\n\nPlease click here to reset your password:\n\n{$confirmation_link}\n\nThanks,\nThe Db-Friends Team";
        $header = "From: anchit97123@gmail.com";
        if (mail($to, $subject, $message, $header)) {
            $_SESSION["flash"] = ["type" => "success", "message" => "If that email exists, you will be emailed a link to reset your password."];
            header("Location:index.php");
        } else {
            echo "Sorry, failed while sending mail!";
        }
    }
}
?>

<div class="row mt-5 justify-content-center signin_row">
    <div class="signin_form_col col-6">
        <h2 class='pb-2 border-bottom'>Forgot Password</h2>
        <form method="POST" action='#'>
            <div id="errorDiv" class="mt-2 text-danger">
                <?php echo $email_error; ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name='email' type="email" class="form-control" id="email" aria-describedby="emailHelp">
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>