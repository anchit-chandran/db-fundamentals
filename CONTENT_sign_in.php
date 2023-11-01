<?php include_once("CONTENT_header.php") ?>
<?php include_once("database.php") ?>
<?php require("utilities.php") ?>


<div class="row justify-content-center signin_row">
    <div class="signin_form_col col-6">
        <form method="POST" action="login_result.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name='email' type="email" class="form-control" id="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name='password' id="exampleInputPassword1">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>