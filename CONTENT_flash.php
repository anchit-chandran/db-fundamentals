<!-- Flash Message -->
<?php
echo "<div class='alert alert-{$_SESSION["flash"]["type"]} mt-4 mb-0' role='alert'>
    {$_SESSION["flash"]["message"]}
    </div>";
unset($_SESSION['flash']);  // clear flash message after displaying
?>