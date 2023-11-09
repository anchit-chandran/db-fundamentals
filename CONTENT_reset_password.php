<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>

<div class="row justify-content-center signin_row">
    <div class="signin_form_col col-6">
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
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
</div>