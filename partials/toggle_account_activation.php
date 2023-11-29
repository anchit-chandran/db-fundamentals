<?php include_once '../database.php' ?>
<?php include_once("../utilities.php");
date_default_timezone_set('Europe/London');
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userToUpdate = runQuery("SELECT * FROM User WHERE userId={$_GET['userId']}");

    runQuery("UPDATE User SET isActive = NOT isActive WHERE userId={$_GET['userId']}");

    $userToUpdate = runQuery("SELECT * FROM User WHERE userId={$_GET['userId']}");

    while ($row = $userToUpdate->fetch_assoc()) {

        $userId = $row['userId'];


        echo "<td>" . $userId . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['firstName'] . "</td>";
        echo "<td>" . $row['lastName'] . "</td>";
        $active_text = $row['isActive'] ? "<p class='form_valid'>Active</p>" : "<p class='form_error'>Disabled</p>";
        echo "<td>" . $active_text . "</td>";
        $superuserText = $row['isSuperuser'] ? "✅" : "❌";
        echo "<td>" . $superuserText . "</td>";
        $toggle_text = $row['isActive'] ? "Disable" : "Activate";
        $toggle_colour = $row['isActive'] ? "btn-danger" : "btn-success";
        echo "<td>" . "<button hx-get='partials/toggle_account_activation.php?userId={$userId}' hx-target='#row-userId-{$userId}' hx-swap='innerHTML' class='btn {$toggle_colour}'>{$toggle_text}</button>" . "</td>";

    }
}
?>