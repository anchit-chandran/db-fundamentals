<?php include_once("CONTENT_header.php") ?>
<?php include_once 'database.php' ?>
<?php include_once("utilities.php") ?>

<?php
// USERS
$users = runQuery("SELECT * FROM User");

// PRODUCTS
$products = runQuery("SELECT O.orderId, O.userId, O.state, O.productId, P.name as 'productName', U.email
FROM Orders as O
JOIN Product as P ON O.productId = P.productId
JOIN User as U ON O.userId = U.userId");


?>

<div class="row">
    <div class="col mt-3">
        <h2>Admin Panel</h2>
        <h4>Users</h4>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Email</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Account Active</th>
                    <th scope="col">Superuser</th>
                    <th scope="col">Activate/Disable Account</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $users->fetch_assoc()) {
                    $userId = $row['userId'];
                    echo "<tr id='row-userId-{$userId}' class> ";
                    echo "<td>" . $userId . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['firstName'] . "</td>";
                    echo "<td>" . $row['lastName'] . "</td>";
                    $active_text = $row['isActive'] ? "<p class='form_valid'>Active</p>" : "<p class='form_error'>Disabled</p>";
                    echo "<td>" . $active_text . "</td>";
                    echo "<td>" . $row['isSuperuser'] . "</td>";
                    $toggle_text = $row['isActive'] ? "Disable" : "Activate";
                    $toggle_colour = $row['isActive'] ? "btn-danger" : "btn-success";
                    echo "<td>" . "<button hx-get='partials/toggle_account_activation.php?userId={$userId}' hx-target='#row-userId-{$userId}' hx-swap='innerHTML' class='btn {$toggle_colour}'>{$toggle_text}</button>" . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <hr>
        <h4>Orders</h4>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Order Id</th>
                    <th scope="col">Email</th>
                    <th scope="col">Status</th>
                    <th scope="col">Product</th>
                    <th scope="col">Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $products->fetch_assoc()) {
                    
                    $orderId = $row['orderId'];
                    $userId = $row['userId'];
                    $status = $row['state'];
                    $productId = $row['productId'];
                    $productName = $row['productName'];
                    $email = $row['email'];

                    echo "<tr id='row-orderId-{$orderId}' class> ";
                        echo "<td>" . $orderId . "</td>";
                        echo "<td>" . $email . "</td>";
                        echo "<td>" . $status . "</td>";
                        echo "<td>" . "<a href='listings?productId={$productId}'>{$productName}</a>" . "</td>";
                        echo "<td>" . "
                        
                        <select class='form-select'>
                            <option selected>Open this select menu</option>
                        </select>
                        
                        " . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <hr>
    </div>
</div>