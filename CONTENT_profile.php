<?php
include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");
?>


<div class="row">
    <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center"><img class="rounded-circle mt-5" width="150px" src="https://t3.ftcdn.net/jpg/05/71/08/24/360_F_571082432_Qq45LQGlZsuby0ZGbrd79aUTSQikgcgc.jpg"><span class="font-weight-bold">FIRSTNAME</span><span class="text-black-50">EMAIL@mail.com.my</span><span> </span></div>
        <div class="mt-5 text-center"><button class="btn btn-primary profile-button" type="button">Update Profile</button></div>
    </div>
    <div class="col-md-5 border-right">
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Profile</h4>
            </div>
            <div class="row mt-2">
                <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" placeholder="first name" value=""></div>
                <div class="col-md-6"><label class="labels">Surname</label><input type="text" class="form-control" value="" placeholder="surname"></div>
            </div>
            <div class="row mt-2">
                <div class="col"><label class="labels">Email</label><input type="text" class="form-control" placeholder="first name" value=""></div>
            </div>
        </div>
        <div class="row">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Payment Details</h4>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="payment-method" class="mx-2">Payment Method</label>
                        <div id="">
                            <select name="payment-method" class="form-control" id="payment-method">
                                <option selected value="1">1</option>
                                <option selected value="2">2</option>
                                <option selected value="3">3</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col"><label class="labels">Details</label><input type="text" class="form-control" placeholder="first name" value=""></div>
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

                    <div class="col-md-12"><label class="labels">Mobile Number</label><input type="text" class="form-control" placeholder="enter phone number" value=""></div>
                    <div class="col-md-12"><label class="labels">Address Line 1</label><input type="text" class="form-control" placeholder="enter address line 1" value=""></div>
                    <div class="col-md-12"><label class="labels">Address Line 2</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>
                    <div class="col-md-12"><label class="labels">Postcode</label><input type="text" class="form-control" placeholder="enter address line 2" value=""></div>


                </div>
            </div>

        </div>
    </div>


</div>
</div>