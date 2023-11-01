<?php

include_once 'database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password_raw = $_POST['password'];

    // Dont use all cols so could clean up if time
    $userFound = runQuery("SELECT *, password FROM User WHERE email='{$email}'");

    if ($userFound) {
        while ($row = $userFound->fetch_assoc()) {
            
            if (password_verify($password_raw, $row['password'])) {
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['isSuperuser'] = $row['isSuperuser'];
                $_SESSION['logged_in'] = True;
            };
        };
    };
} 

header("refresh:0;url=index.php");
