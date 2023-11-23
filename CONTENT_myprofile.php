<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

$is_edit = False;
$userId = $_SESSION['userId'];

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['is_edit'])) {
        $is_edit = $_GET['is_edit'];
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $paymentMethod = $_POST['paymentMethod'];
    $paymentDetails = $_POST['paymentDetails'];
    $phoneNumber = $_POST['phoneNumber'];
    $address_1 = $_POST['address_1'];
    $address_2 = $_POST['address_2'];
    $address_3 = $_POST['address_3'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $zipCode = $_POST['zipCode'];

    $updateUserQuery = "UPDATE User SET firstName = '{$firstName}', lastName = '{$lastName}', email = '{$email}' WHERE userId = {$userId}";
    $updatePaymentQuery = "UPDATE Payment SET paymentMethod = '{$paymentMethod}', paymentDetails = '{$paymentDetails}' WHERE userId = {$userId}";
    $updateAddressQuery = "UPDATE Address SET phoneNumber = '{$phoneNumber}', address_1 = '{$address_1}', address_2 = '{$address_2}', address_3 = '{$address_3}', city = '{$city}', country = '{$country}', zipCode = '{$zipCode}' WHERE userId = {$userId}";

    runQuery($updateUserQuery);
    runQuery($updatePaymentQuery);
    runQuery($updateAddressQuery);

    header("Location:profile.php");
}

$userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();

function renderValueDisabledAttributes($field, $table = null, $_isEdit = null)
{
    global $userDetails, $is_edit; // Use the global keyword to access the variables outside the function.

    // Set default values if they are not provided
    if ($table === null) {
        $table = $userDetails; // Replace with your default value
    }
    if ($_isEdit === null) {
        $_isEdit = $is_edit; // Replace with your default value
    }

    echo "value= '{$table[$field]}' ";
    echo $_isEdit ? '' : 'disabled';
}

$paymentDetails = runQuery("SELECT * FROM Payment WHERE userId = {$userId}")->fetch_assoc();
$addressDetails = runQuery("SELECT * FROM Address WHERE userId = {$userId}")->fetch_assoc();

?>


<form class="row" method='POST'>
    <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center">

            <img class="rounded-circle mt-5" width="150px" src="https://t3.ftcdn.net/jpg/05/71/08/24/360_F_571082432_Qq45LQGlZsuby0ZGbrd79aUTSQikgcgc.jpg">

            <span class="font-weight-bold">
                <?php echo ucfirst($userDetails['firstName']) ?>
            </span>
            <span class="text-black-50">
                <?php echo $userDetails['email'] ?>
            </span>

        </div>
        <div class="col mt-5 text-center">
            <?php
            if ($is_edit) {
                echo "<button class='btn btn-success profile-button w-100' type='submit'>Confirm</button>";
            } else {
                echo "<a class='btn btn-primary profile-button w-100' type='button' href='https://localhost/db-fundamentals/profile.php?is_edit=true'>Update Profile</a>";
            }
            ?>

        </div>
        <div class="col mt-3 d-flex justify-content-center align-items-center">
            <?php
            echo "<a href='profile.php?userId={$userId}' class='btn btn-success w-100'>Public Profile</a>"
            ?>
        </div>
        <div class="col mt-3 d-flex justify-content-center align-items-center">
            <a href="forgot_password.php" class="btn btn-secondary w-100">Reset password</a>
        </div>
    </div>
    <div class="col-md-5 border-right">
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Profile</h4>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="labels">First Name</label>
                    <input  name='firstName' type="text" class="form-control" placeholder="First Name" <?php renderValueDisabledAttributes($field = 'firstName') ?>>
                </div>
                <div class="col-md-6">
                    <label class="labels">Last Name</label><input placeholder='Last Name' name='lastName' type="text" class="form-control" <?php renderValueDisabledAttributes($field = 'lastName') ?>>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col"><label class="labels">Email</label>
                    <input name='email' type="text" class="form-control" placeholder="Email" <?php renderValueDisabledAttributes($field = 'email') ?>>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
                <h4 class="text-right">Payment Details</h4>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <label class="labels">Payment Method</label>
                    <input name='paymentMethod' type="text" class="form-control" placeholder="Payment Method" <?php
                                                                                        renderValueDisabledAttributes($field = 'paymentMethod', $table = $paymentDetails);

                                                                                        ?>>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <label class="labels">Details</label>
                    <input name='paymentDetails' type="text" class="form-control" placeholder="Payment Details" <?php renderValueDisabledAttributes($field = 'paymentDetails', $table = $paymentDetails);  ?>>
                </div>
            </div>


        </div>

    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Address</h4>
                </div>


                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Mobile Number</label>
                        <input name='phoneNumber' type="text" class="form-control" placeholder="Enter phone number" <?php renderValueDisabledAttributes($field = 'phoneNumber', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Address Line 1</label>
                        <input name='address_1' type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_1', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Address Line 2</label>
                        <input name='address_2' type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_2', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Address Line 3</label>
                        <input name='address_3' type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_3', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">City</label>
                        <input name='city' type="text" class="form-control" placeholder="City" <?php renderValueDisabledAttributes($field = 'city', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Country</label>
                        <input name='country' type="text" class="form-control" placeholder="Country" <?php renderValueDisabledAttributes($field = 'country', $table = $addressDetails);  ?>>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12"><label class="labels">Postcode</label>
                        <input name='zipCode' type="text" class="form-control" placeholder="Postcode" <?php renderValueDisabledAttributes($field = 'zipCode', $table = $addressDetails);  ?>>
                    </div>
                </div>



            </div>

        </div>
    </div>


</form>