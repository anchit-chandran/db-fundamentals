<?php include_once("CONTENT_header.php") ?>
<?php include_once("database.php") ?>
<?php require("utilities.php") ?>


<div class="row">
    <div class="col">
        <form method="POST" action="login_result.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name='email' id="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name='password' id="password" placeholder="Password">
            </div>
            <button type="button" value='submit' class="btn btn-primary form-control">Sign in</button>
        </form>
    </div>
</div>