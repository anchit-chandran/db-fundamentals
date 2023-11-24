<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = 'http://localhost/db-fundamentals';
?>