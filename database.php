<?php
function connectToDatabase()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db-fundamentals";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function runQuery($query) {
    $conn = connectToDatabase();

    $result = $conn->query($query);

    if (!$result) {
        echo "Query error: " . $conn->error;
        $conn->close();
        return null;
    }

    $conn->close();
    return $result;
}