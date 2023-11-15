<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = 'https://localhost/db-fundamentals';
?>