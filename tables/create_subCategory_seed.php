<?php

// Contains db connection code
include_once 'database.php';

echo "<h1>SubCategory Table</h1> <br>";

// Drop table if exists
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 0;");
$dropSql = "DROP TABLE IF EXISTS SubCategory";
$tableExists = runQuery($dropSql);

if ($tableExists) {
    echo "Old Table SubCategory dropped. <br>";
} else {
    echo "Error dropping table.  <br>";
}
runQuery("SET GLOBAL FOREIGN_KEY_CHECKS = 1;");

// create SubCategory table
$createSubCategoryTable = "CREATE TABLE SubCategory (
      subCategoryId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      subCategoryName VARCHAR(100),
      categoryId INT NOT NULL,
      FOREIGN KEY (categoryId) REFERENCES Category(categoryId) ON DELETE CASCADE
  ) ENGINE=INNODB;
  ";
if (runQuery($createSubCategoryTable)) {
    echo "Successfully created SubCategory Table <br>";
} else {
    echo "Error creating SubCategory table  <br>";
}

$seedSubCategorys = "INSERT INTO SubCategory (subCategoryName, categoryId)
    VALUES 
    ('Cheese', 1),
    ('Food but not cheese', 1),
    ('TEST ITEM', 1),
    ('Toothbrush', 2);";

if (runQuery($seedSubCategorys)) {
    echo "Successfully seeded SubCategorys. <br>";
} else {
    echo "Error seeding SubCategorys <br>";
}

// Look at SubCategorys
$getAllSubCategoryTable = "SELECT * FROM SubCategory";
$subCategoryTable = runQuery($getAllSubCategoryTable);
if ($subCategoryTable) {
    // Loop through each row in the result set
    while ($row = $subCategoryTable->fetch_assoc()) {
        echo "-----------------------<br>";
        echo "SubCategory ID: " . $row['subCategoryId'] . "<br>";
        echo "SubCategory Name: " . $row['subCategoryName'] . "<br>";
        echo "Category ID: " . $row['categoryId'] . "<br>";
    }
    echo "-----------------------<br>";
} else {
    echo "Error executing query.";
}

?>