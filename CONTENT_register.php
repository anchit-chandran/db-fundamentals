<div class="row justify-content-center signin_row">
    <div class="signin_form_col col-6">
        <h2 class='pb-2 border-bottom'>Register</h2>
        <form method="POST" action='#'>
            <!-- <div id="errorDiv" class="mt-2 text-danger">
                <?php if (isset($failed_login)) {
                    echo $failed_login;
                } ?>
            </div> -->
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">First name</label>
                    <input id='first_name' name='first_name' type="text" class="form-control" aria-label="First name">
                </div>
                <div class="col">
                    <label for="last_name" class="form-label">Last name</label>
                    <input id='last_name' name='last_name' type="text" class="form-control" aria-label="Last name">
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name='email' type="email" class="form-control" id="email" aria-describedby="emailHelp" hx-post="partials/check_email.php" hx-trigger="keyup" hx-target="#email_error" hx-swap="innerHTML">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                <div id='email_error'></div>
            </div>
            <div class="mb-3">
                <label for="inputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name='password1' id="inputPassword1">
            </div>
            <div class="mb-3">
                <label for="inputPassword2" class="form-label">Repeat password</label>
                <input type="password" class="form-control" name='password2' id="inputPassword2" aria-describedby="pw2Help">
                <div id="pw2Help" class="form-text">Please type your password again.</div>
            </div>
            <button type="submit" class="btn btn-primary">Create account</button>
            <a class="btn btn-secondary" href='login.php'>Login</a>
        </form>
    </div>
</div>