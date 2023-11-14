<?php include_once 'database.php' ?>

<?php

// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     print_r( $_GET["category-option"] );
// }

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if ($_GET["category-option"] == "all") {
        $getSubCategories = 'SELECT * FROM SubCategory';
    } else {
        $getSubCategories = 'SELECT * FROM SubCategory WHERE categoryId =' . $_GET["category-option"];
    }
    $subCategories = runQuery($getSubCategories);
    if ($subCategories) {
        echo '<select class="form-control" id="subcat">';
        while ($row = $subCategories->fetch_assoc()) {
            echo "<option value =" . $row['subCategoryId'] . ">" . $row['subCategoryName'] . "</option>";
        }
        echo '</select>';
    }
}


// echo '<select class="form-control" id="subcat">';
// echo '<option value="fill">' . '</option>';
// echo '</select>';

?>




<!-- fetch subcatgories matching the category -->
<!-- <select class="form-control" id="subcat">
    <option selected value="all">All subcategories</option>
    <option value="fill">Fill me in</option>
    <option value="with">with options</option>
    <option value="populated">populated from a database?</option>
</select> -->