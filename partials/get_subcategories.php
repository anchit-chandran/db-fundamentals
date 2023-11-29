<?php

include_once("../database.php");
include_once("../utilities.php");
date_default_timezone_set('Europe/London');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ($_GET["category-option"] == "all") {
        echo "<select name='subcat-option' class='form-control' id='subcat' disabled>
        <option selected value='all'>-</option>
      </select>";
    } else {
        $getSubCategories = 'SELECT * FROM SubCategory WHERE categoryId =' . $_GET["category-option"];
        $subCategories = runQuery($getSubCategories);
        if ($subCategories) {
            echo '<select name="subcategory" class="form-control" id="subcat">';
            while ($row = $subCategories->fetch_assoc()) {
                echo "<option value =" . $row['subCategoryId'] . ">" . $row['subCategoryName'] . "</option>";
            }
            echo '</select>';
        }
    }
}
