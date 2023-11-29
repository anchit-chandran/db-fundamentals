<?php include_once '../database.php' ?>
<?php include_once("../utilities.php");
date_default_timezone_set('Europe/London');
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $orderId =  $_GET['orderId'];
    $status_option_selected = $_GET['status_select'];
    runQuery("UPDATE Orders SET state = '{$status_option_selected}' WHERE orderId={$orderId}");

    $orders = runQuery(
        "SELECT O.orderId, O.userId, O.state, O.productId, P.name as 'productName', U.email
        FROM Orders as O
        JOIN Product as P ON O.productId = P.productId
        JOIN User as U ON O.userId = U.userId
        WHERE orderId = $orderId"
    );

    $status_options = array("Processing", "Shipped", "Delivered");
    $status_option_els = [];
    foreach ($status_options as $status_option) {
        $status_option_els[] = "<option value='{$status_option}'>{$status_option}</option>";
    }

    $row = $orders->fetch_assoc();

    $orderId = $row['orderId'];
    $userId = $row['userId'];
    $status = $row['state'];

    $productId = $row['productId'];
    $productName = $row['productName'];
    $email = $row['email'];

    $delivered = $status == "Delivered" ? "table_row_muted" : "";

    echo "<td class='{$delivered}'>" . $orderId . "</td>";
    echo "<td class='{$delivered}'>" . $email . "</td>";
    echo "<td class='{$delivered}'>" . $status . "</td>";
    echo "<td class='{$delivered}'>" . "<a href='listings?productId={$productId}'>{$productName}</a>" . "</td>";
    echo "<td class='{$delivered}'>";
    echo "<select class='form-select {$delivered}' name='status_select' hx-get='partials/update_order_status.php?orderId={$orderId}&status={$status}' hx-target='#row-orderId-{$orderId}'>";
    foreach ($status_option_els as $status_option_el) {
        if ($status_option_el == "<option value='{$status}'>{$status}</option>")
            echo "<option value='{$status}' selected>{$status}</option>";
        else
            echo $status_option_el;
    }
    echo "</select>";
}
?>