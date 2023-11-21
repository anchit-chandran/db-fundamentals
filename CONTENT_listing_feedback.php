<div>
    <h3>Product Feedback</h3>
    <p>
        Rating: <?php echo $feedback['rating'] . "/5"; ?>
    </p>

    <?php 
        if ($feedback['comment'] != null) {
            echo "<p> Comment: ";
            echo $feedback['comment'];
            echo "</p>";
        }
    ?>
</div>