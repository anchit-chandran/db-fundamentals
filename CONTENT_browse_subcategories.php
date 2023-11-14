<?php include_once 'database.php' ?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ($_GET["category-option"] == "all") {
        $getSubCategories = 'SELECT * FROM SubCategory';
    } else {
        $getSubCategories = 'SELECT * FROM SubCategory WHERE categoryId =' . $_GET["category-option"];
    }
    $subCategories = runQuery($getSubCategories);
    if ($subCategories) {
        echo '<select class="form-control" id="subcat">';
        echo '<option selected value="all">All Subcategories</option>';
        while ($row = $subCategories->fetch_assoc()) {
            echo "<option value =" . $row['subCategoryId'] . ">" . $row['subCategoryName'] . "</option>";
        }
        echo '</select>';
    }
}

?>