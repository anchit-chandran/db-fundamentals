<?php
include_once("../database.php");
include_once("../utilities.php");

// [search_term] => [category-option] => 1 [subcategory] => 1 [sort-option] => bidlow
print_r($_GET);
if ($_SERVER['REQUEST_METHOD']=="GET") {
    
    $product_table_query = "SELECT * FROM Product ";

    $where_conditions = array();
    $order_by_conditions = array();

    // GO THROUGH GET PARAMETERS, APPENDING CONDITIONS TO SQL QUERY
    if ($_GET["search_term"]) {
        $search_term = $_GET["search_term"];
        $where_conditions[] = "name like' %{$search_term}%'";
    }

    if (isset($_GET["subcategory"])) {
        $subcategoryId = $_GET["subcategory"];
        $where_conditions[] = "subcategoryID = {$subcategoryId}";
    }

    // SORT ON BIDS
    if ($_GET["sort-option"]) {
        $sort_options = explode('-',$_GET["sort-option"]);
        print_r($sort_options);
        $order_by_conditions[] = "";
    }

    echo '<br>';
    echo '<pre>';
    print_r($where_conditions);
    echo $product_table_query;
    echo '</pre>';
}
?>

