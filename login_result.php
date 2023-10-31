<?php

include_once 'database.php';

session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password_raw = $_POST['password'];

    $userFound = runQuery("SELECT email, password FROM User WHERE email='{$email}'");

    if ($userFound) {
        while ($row = $userFound->fetch_assoc()) {
            
            if (password_verify($password_raw, $row['password'])) {
                echo 'logging in';
            } else {
                echo 'user not found';
            };
        }

    } else {
        echo 'User does not exist';
    }
} 

header("refresh:1;url=index.php");
