<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo '<pre>';
    print_r( $_GET );
    echo '</pre>';
}

?>
<!-- fetch subcatgories matching the category -->
<!-- <select class="form-control" id="subcat">
    <option selected value="all">All subcategories</option>
    <option value="fill">Fill me in</option>
    <option value="with">with options</option>
    <option value="populated">populated from a database?</option>
</select> -->
