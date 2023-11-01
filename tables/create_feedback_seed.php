<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Feedback Table</h1> <br>";

// Drop table if exists
$dropSql = "DROP TABLE IF EXISTS Feedback";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Feedback dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}

// create Feedback table
$createFeedbackTable = "CREATE TABLE Feedback (
    feedbackId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    comment VARCHAR(100) NOT NULL,
    rating INT,
    productId INT NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (productId) REFERENCES Product(productId),
    FOREIGN KEY (userId) REFERENCES User(userId)
) ENGINE=INNODB;

  ";
if (runQuery($createFeedbackTable)) {
    echo "Successfully created Feedback Table <br>";
} else {
    echo "Error creating Feedback table <br>";
}

$seedFeedbacks = "INSERT INTO Feedback (comment, rating, productId, userId)
    VALUES 
    ('Nice product. Highly Recommended', 5, 1, 1),
    ('Order did not arrive', 1, 2, 1),
    ('Ok but could be better', 3, 1, 2);";

if (runQuery($seedFeedbacks)) {
    echo "Successfully seeded Feedbacks. <br>";
} else {
    echo "Error seeding Feedbacks <br>";
}

// Look at Feedbacks
$getAllFeedbackTable = "SELECT * FROM Feedback";
$feedbackTable = runQuery($getAllFeedbackTable);
if ($feedbackTable) {
    // Loop through each row in the result set
    while ($row = $feedbackTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "Feedback ID: " . $row['feedbackId'] . "<br>";
        echo "Comment: " . $row['comment'] . "<br>";
        echo "Rating: " . $row['rating'] . "<br>";
        echo "Product ID: " . $row['productId'] . "<br>";
        echo "User ID: " . $row['userId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>