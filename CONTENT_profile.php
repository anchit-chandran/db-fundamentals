<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

$userId = $_SESSION['userId'];
$userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();
$is_edit = False;
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


<div class="row">
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
        <div class="col mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Update Profile</button></div>
        <div class="col mt-3 d-flex justify-content-center align-items-center"><a href="forgot_password.php" class="btn btn-secondary">Reset password</a></div>
    </div>
    <div class="col-md-5 border-right">
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Profile</h4>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="labels">Name</label>
                    <input type="text" class="form-control" placeholder="first name" <?php renderValueDisabledAttributes($field = 'firstName') ?>>
                </div>
                <div class="col-md-6">
                    <label class="labels">Surname</label><input type="text" class="form-control" <?php renderValueDisabledAttributes($field = 'lastName') ?>>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col"><label class="labels">Email</label>
                    <input type="text" class="form-control" placeholder="first name" <?php renderValueDisabledAttributes($field = 'email') ?>>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Payment Details</h4>
                </div>
                <div class="row mt-2">
                    <div class="col"><label class="labels">Payment Method</label>
                        <input type="text" class="form-control" placeholder="first name" <?php
                                                                                            renderValueDisabledAttributes($field = 'paymentMethod', $table = $paymentDetails);

                                                                                            ?>>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col"><label class="labels">Details</label>
                        <input type="text" class="form-control" placeholder="first name" <?php renderValueDisabledAttributes($field = 'paymentDetails', $table = $paymentDetails);  ?>>
                    </div>
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

                        <input type="text" class="form-control" placeholder="enter phone number" <?php renderValueDisabledAttributes($field = 'phoneNumber', $table = $addressDetails);  ?>>
                    </div>
                    <div class="col-md-12"><label class="labels">Address Line 1</label>

                        <input type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_1', $table = $addressDetails);  ?>>

                    </div>
                    <div class="col-md-12"><label class="labels">Address Line 2</label>

                        <input type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_2', $table = $addressDetails);  ?>>

                    </div>
                    <div class="col-md-12"><label class="labels">Address Line 3</label>

                        <input type="text" class="form-control" placeholder="-" <?php renderValueDisabledAttributes($field = 'address_3', $table = $addressDetails);  ?>>

                    </div>
                    <div class="col-md-12"><label class="labels">City</label>

                        <input type="text" class="form-control" placeholder="City" <?php renderValueDisabledAttributes($field = 'city', $table = $addressDetails);  ?>>

                    </div>
                    <div class="col-md-12"><label class="labels">Country</label>

                        <input type="text" class="form-control" placeholder="Country" <?php renderValueDisabledAttributes($field = 'country', $table = $addressDetails);  ?>>

                    </div>
                    <div class="col-md-12"><label class="labels">Postcode</label>

                        <input type="text" class="form-control" placeholder="zip code" <?php renderValueDisabledAttributes($field = 'zipCode', $table = $addressDetails);  ?>>

                    </div>


                </div>
            </div>

        </div>
    </div>


</div>