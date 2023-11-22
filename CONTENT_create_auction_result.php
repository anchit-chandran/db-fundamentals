<!-- Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '*)-0-(0%()(?' at line 1Error inserting data -->
<div class="container my-5">

<?php

include_once 'database.php';
function checkTitle($title) {
    if ($title == "" or strlen($title) > 500) {
        return false;
    } else {
        return true;
    }
}

function checkDetails($details) {
    if ($details == "" or strlen($details) > 1000) {
        return false;
    } else {
        return true;
    }
}

function checkDates($auctionStart, $auctionEnd) {
    if ($auctionStart <= $auctionEnd) {
        return true;
    } else {
        return false;
}
}

function checkCondition($condition) {
    $conditions = array("Brand New", "Slightly Used", "Used");
    if ($condition == "" or  !in_array($condition, $conditions)) {
        return false;
    } else {
        return true;
    }
}

function checkPrices($startPrice, $reservePrice) {
    if (!is_numeric($startPrice) or !is_numeric($reservePrice)) {
        return false;
    } else {
        return true;
    }
}

function checkSubCategory($subcategory , $category) {

    $query = "SELECT categoryId FROM SubCategory WHERE subCategoryId = '{$subcategory}'";
    $result = runQuery($query);
    
    $table_category = array();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $table_category[] = $row;
        }   
        if (count($table_category) !== 1 or join('', reset($table_category)) !== $category) {
            return false;
        } else {
            return true;
        }
    } else {
    
        return false;
    }
}



include_once("database.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $formErrors = array();
    $validForm = true;
    $userId = $_SESSION["userId"];
    $title = trim($_POST["auctionTitle"]);
    $details = trim($_POST["auctionDetails"]);
    $category = $_POST["auctionCategory"];
    $subcategory = $_POST["auctionSubCategory"];
    $condition = $_POST["auctionCondition"];
    $auctionStart = $_POST["auctionStartDate"];
    $auctionEnd = $_POST["auctionEndDate"];
    $startPrice = number_format(round(trim($_POST["auctionStartPrice"]), 2), 2);
    if ($_POST["auctionReservePrice"] === "") {
        $reservePrice = 0;
    } else {
        $reservePrice = number_format(round(trim($_POST["auctionReservePrice"]), 2), 2);
    }
    if (isset($_FILES["auctionImage"])) {
        $imageTmpPath = $_FILES["auctionImage"]["tmp_name"];
        $auctionImage = addslashes(file_get_contents($imageTmpPath));
    } else {
        $auctionImage = null;
    }
    $check = checkDates($auctionStart, $auctionEnd);
    $catcheck = checkSubCategory($subcategory, $category);
    if (!checkTitle($title)) {
        $validForm = false;
        array_push($formErrors, "Error with auction title");
    }
    if (!checkDetails($details)) {
        $validForm = false;
        array_push($formErrors, "Error with auction details");
    }
    if (!checkCondition($condition)) {
        $validForm = false;
        array_push($formErrors, "Error with item condition");
    }
    if (!checkPrices($startPrice, $reservePrice)) {
        $validForm = false;
        array_push($formErrors, "Error with auction prices");
    }
    if (!checkSubCategory($subcategory, $category)) {
        
        $validForm = false;
        array_push($formErrors, "Error with auction subcategory");
    }
    if (!checkDates($auctionStart, $auctionEnd)) {
        $validForm = false;
        array_push($formErrors, "Error with auction start and end dates");
    };

    

    if ($validForm) {
        $currentDateTime = date("Y-m-d H:i:s");
        
        $query = "INSERT INTO Product (name, description, auctionStartDatetime, auctionEndDatetime, reservePrice, startPrice, createdAt, image, state, userId, subcategoryId) VALUES ('{$title}', '{$details}', '{$auctionStart}', '{$auctionEnd}', '{$reservePrice}', '{$startPrice}', '{$currentDateTime}', '{$auctionImage}', '{$condition}', '{$userId}', '{$subcategory}')";
        $result = runQuery($query);
        if ($result) {
            echo('<div class="text-center alert alert-success">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');
        } else {
            echo "Error inserting data";
        }
        
    } else {
        foreach ($formErrors as $error) {
            echo $error . "<br>";
        }

}
}
 

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */


/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */
            

// If all is successful, let user know.


?>

</div>



