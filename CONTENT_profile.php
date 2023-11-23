<?php

include_once("CONTENT_header.php");
include_once("database.php");
include_once("utilities.php");

$userId = $_GET['userId'];

$userDetails = runQuery("SELECT * FROM User WHERE userId = {$userId}")->fetch_assoc();

?>

<form class="row" method='POST'>
    <div class="col-md-3 border-right">
        <div class="d-flex flex-column align-items-center text-center">

            <img class="rounded-circle mt-5" width="150px" src="https://t3.ftcdn.net/jpg/05/71/08/24/360_F_571082432_Qq45LQGlZsuby0ZGbrd79aUTSQikgcgc.jpg">

            <span class="font-weight-bold">
                <?php echo ucfirst($userDetails['firstName']) ?>
            </span>

        </div>

    </div>
    <div class="col border-right">
        <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Auctions</h4>
            </div>

        </div>

    </div>



</form>