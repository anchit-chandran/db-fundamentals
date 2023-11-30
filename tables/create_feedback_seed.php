<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>Feedback Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS Feedback";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table Feedback dropped. <br>";
} else {
    echo "Error dropping table. <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create Feedback table
$createFeedbackTable = "CREATE TABLE Feedback (
    feedbackId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    comment VARCHAR(2000),
    rating INT,
    productId INT NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (productId) REFERENCES Product(productId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
) ENGINE=INNODB;

  ";
if (runQuery($createFeedbackTable)) {
    echo "Successfully created Feedback Table <br>";
} else {
    echo "Error creating Feedback table <br>";
}

$seedFeedbacks = "INSERT INTO Feedback (comment, rating, productId, userId)
    VALUES 
    ('Nice product. Highly Recommended.', 5, 4, 5),
    ('Not gonna lie, spent way too much on this apple', 4, 5, 1),
    ('It\'s alright. Bit sour for my taste.', 4, 9, 1),
    ('Order did not arrive.', 1, 1, 1);";

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