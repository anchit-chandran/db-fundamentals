<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Payment Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Payment";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Payment dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Payment table
$createPaymentTable = "CREATE TABLE Payment (
      paymentId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      paymentMethod VARCHAR(100) NOT NULL,
      paymentDetails VARCHAR(500) NOT NULL,
      userId INT NOT NULL,
      FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
  ) ENGINE=INNODB;
  ";
if (runQuery($createPaymentTable)) {
    echo "Successfully created Payment Table <br>";
} else {
    echo "Error creating Payment table <br>";
}

$seedPayments = "INSERT INTO Payment (paymentMethod, paymentDetails, userId)
    VALUES 
    ('Direct Debit', '1234 1234 1234 1234', 6),
    ('Direct Debit', 'ABCD BDCA CDAB DCBA', 3),
    ('Paypal', 'Account Number YYYY YYYY', 2);";

if (runQuery($seedPayments)) {
    echo "Successfully seeded Payments. <br>";
} else {
    echo "Error seeding Payments <br>";
}

// Look at Payment
$getAllPaymentTable = "SELECT * FROM Payment";
$paymentTable = runQuery($getAllPaymentTable);
if ($paymentTable) {
    // Loop through each row in the result set
    while ($row = $paymentTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Payment ID: " . $row['paymentId'] . "<br>";
        echo "Payment Method: " . $row['paymentMethod'] . "<br>";
        echo "Payment Details: " . $row['paymentDetails'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>