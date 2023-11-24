<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
include_once("config.php");
?>


<?php

function checkUniqueEmail($email)
{
    $queryEmail = "SELECT email 
                    FROM User
                    WHERE email = '{$email}';";

    if (mysqli_num_rows(runQuery($queryEmail)) >= 1) {
        return false;
    } else {
        return true;
    }
}

function renderFormErrors($errors)
{
    if (count($errors) > 0) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li class='form_error'>{$error}</li>";
        }
        echo "</ul>";
    }
}

$formErrors = [
    "email" => [],
    "password1" => [],
    "password2" => [],
    "firstName" => [],
    "lastName" => [],
    "paymentMethod" => [],
    "paymentDetails" => [],
    "phoneNumber" => [],
    "address_1" => [],
    "address_2" => [],
    "address_3" => [],
    "city" => [],
    "country" => [],
    "postcode" => [],
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    $paymentMethod = $_POST['paymentMethod'];
    $paymentDetails = $_POST['paymentDetails'];

    $phoneNumber = $_POST['phoneNumber'];
    $address_1 = $_POST['address_1'];
    $address_2 = $_POST['address_2'];
    $address_3 = $_POST['address_3'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $postcode = $_POST['postcode'];

    $form_is_valid = true;

    // VALIDATE EMAIL
    if (!(filter_var($email, FILTER_VALIDATE_EMAIL) and checkUniqueEmail($email))) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($formErrors["email"], "Please enter a valid email.");
        }
        if (!checkUniqueEmail($email)) {
            array_push($formErrors["email"], "That email is already taken.");
        }

        $form_is_valid = false;
    }

    // VALIDATE PASSWORD

    // Minimum length of 8 characters
    if (strlen($password1) < 8) {
        array_push($formErrors["password1"], "Must be at least 8 characters long.");
        $form_is_valid = false;
    }

    // Check for at least one number
    if (!preg_match('/\d/', $password1)) {
        array_push($formErrors["password1"], "Needs at least 1 number.");
        $form_is_valid = false;
    }

    // Check for at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password1)) {
        array_push($formErrors["password1"], "Needs at least 1 uppercase letter.");
        $form_is_valid = false;
    }

    // Check for at least one lowercase letter
    if (!preg_match('/[a-z]/', $password1)) {
        array_push($formErrors["password1"], "Needs at least 1 lowercase letter.");
        $form_is_valid = false;
    }

    // Check for at least one special character
    if (!preg_match('/[\W]/', $password1)) {
        array_push($formErrors["password1"], "Needs at least 1 special character like <em>!@#$%^&*()_+{}[]|?/<>.,</em>");
        $form_is_valid = false;
    }

    //  NOT MATCHING
    if ($password1 == $password2) {
        $hashedPass = password_hash($password1, PASSWORD_DEFAULT);
    } else {
        array_push($formErrors["password1"], "Passwords don't match.");
        $form_is_valid = false;
    }

    if ($form_is_valid) {

        // CREATE USER
        $queryInsertNewUser = "INSERT INTO User 
        (email, password, firstName, lastName, isActive, isSuperuser)
        VALUES 
        ('{$email}', '{$hashedPass}', '{$firstName}', '{$lastName}', FALSE, FALSE);";

        runQuery($queryInsertNewUser);

        // GET USER ID
        $userId = runQuery("SELECT userId FROM User WHERE email = '{$email}'")->fetch_assoc()['userId'];

        // INSERT PAYMENT
        $updatePaymentQuery = "INSERT INTO Payment (paymentMethod, paymentDetails, userId) VALUES ('{$paymentMethod}', '{$paymentDetails}', {$userId})";
        runQuery($updatePaymentQuery);

        // INSERT ADDRESS
        $updateAddressQuery = "INSERT INTO Address (phoneNumber, address_1, address_2, address_3, city, country, zipCode, userId) VALUES ('{$phoneNumber}', '{$address_1}', '{$address_2}', '{$address_3}', '{$city}', '{$country}', '{$postcode}', {$userId})";
        runQuery($updateAddressQuery);

        // SEND EMAIL IF CONFIG.EMAIL_SENDING == True
        if ($EMAIL_SENDING) {

            $token = password_hash($email, PASSWORD_DEFAULT);

            $to = $email;
            $subject = "Confirm registration";
            $confirmation_link = "http://localhost/db-fundamentals/confirm_email.php?token={$token}&email={$email}";
            $message = "Hi {$firstName},\n\nPlease click here to activate your account:\n\n{$confirmation_link}\n\nThanks,\nThe Db-Friends Team";
            $header = "From: anchit97123@gmail.com";
            if (!mail($to, $subject, $message, $header)) {
                echo "Sorry, failed while sending mail!";
            }
        }

        header("Location:successful_registration.php");
    }
}
?>

<div class="row justify-content-center signin_row py-5">
    <div class="signin_form_col col-6">
        <h2 class='pb-2 border-bottom'>Register</h2>
        <form method="POST" action='#'>
            <div class="row">
                <div class="col">
                    <label for="firstName" class="form-label">First name</label>
                    <?php renderFormErrors($formErrors["firstName"]); ?>
                    <input id='firstName' name='firstName' type="text" class="form-control" aria-label="First name">
                </div>
                <div class="col">
                    <label for="lastName" class="form-label">Last name</label>
                    <?php renderFormErrors($formErrors["lastName"]); ?>
                    <input id='lastName' name='lastName' type="text" class="form-control" aria-label="Last name">
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>

                <?php renderFormErrors($formErrors["email"]); ?>

                <input name='email' type="email" class="form-control" id="email" aria-describedby="emailHelp" hx-post="partials/check_email.php" hx-trigger="keyup" hx-target="#email_error" hx-swap="innerHTML">
                <div id='email_error' class='py-2'></div>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="inputPassword1" class="form-label">Password</label>
                <?php renderFormErrors($formErrors["password1"]); ?>
                <input type="password" class="form-control" name='password1' id="inputPassword1">
            </div>
            <div class="mb-3">
                <label for="inputPassword2" class="form-label">Repeat password</label>
                <?php renderFormErrors($formErrors["password2"]); ?>
                <input type="password" class="form-control" name='password2' id="inputPassword2" aria-describedby="pw2Help">
                <div id="pw2Help" class="form-text">Please type your password again.</div>
            </div>

            <!-- PAYMENT DETAILS -->
            <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
                <h4 class="text-right">Payment Details</h4>
            </div>
            <div class="mb-3">
                <label for="paymentMethod" class="form-label">Payment Method</label>
                <?php renderFormErrors($formErrors["paymentMethod"]); ?>
                <select type="password" class="form-control" name='paymentMethod' id="paymentMethod">
                    <option value="Direct Debit">Direct Debit</option>
                    <option value="Paypal">Paypal</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="paymentDetails" class="form-label">Details</label>
                <?php renderFormErrors($formErrors["paymentDetails"]); ?>
                <input type="text" class="form-control" name='paymentDetails' id="paymentDetails">
            </div>

            <!-- ADDRESS -->
            <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
                <h4 class="text-right">Address</h4>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Mobile Number</label>
                <?php renderFormErrors($formErrors["phoneNumber"]); ?>
                <input type="text" class="form-control" name='phoneNumber' id="phoneNumber">
            </div>
            <div class="mb-3">
                <label for="address_1" class="form-label">Address 1</label>
                <?php renderFormErrors($formErrors["address_1"]); ?>
                <input type="text" class="form-control" name='address_1' id="address_1">
            </div>
            <div class="mb-3">
                <label for="address_2" class="form-label">Address 2</label>
                <?php renderFormErrors($formErrors["address_2"]); ?>
                <input type="text" class="form-control" name='address_2' id="address_2">
            </div>
            <div class="mb-3">
                <label for="address_3" class="form-label">Address 3</label>
                <?php renderFormErrors($formErrors["address_3"]); ?>
                <input type="text" class="form-control" name='address_3' id="address_3">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <?php renderFormErrors($formErrors["city"]); ?>
                <input type="text" class="form-control" name='city' id="city">
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <label for="country" class="form-label">Country</label>
                    <?php renderFormErrors($formErrors["country"]); ?>
                    <input type="text" class="form-control" name='country' id="country">
                </div>
                <div class="col-6">
                    <label for="postcode" class="form-label">Postcode</label>
                    <?php renderFormErrors($formErrors["postcode"]); ?>
                    <input type="text" class="form-control" name='postcode' id="postcode">
                </div>
            </div>

            <!--  SUBMIT BUTTONS -->
            <button type="submit" class="btn btn-primary">Create account</button>
            <a class="btn btn-secondary" href='login.php'>Login</a>
        </form>
    </div>
</div>