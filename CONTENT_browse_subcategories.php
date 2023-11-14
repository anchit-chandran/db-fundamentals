<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    print_r( $_GET["category-option"] );
    // echo '<select class="form-control" id="subcat">';
    // echo '<option value="fill">' . '</option>';
    // echo '</select>';
}

?>
<!-- fetch subcatgories matching the category -->
<!-- <select class="form-control" id="subcat">
    <option selected value="all">All subcategories</option>
    <option value="fill">Fill me in</option>
    <option value="with">with options</option>
    <option value="populated">populated from a database?</option>
</select> -->
