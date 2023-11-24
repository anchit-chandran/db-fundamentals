<?php 
    $rating = $feedback['rating']
?>

<div>
    <h3>Product Feedback</h3>
    <p>
        Product rating: 
        <?php 
            include("CONTENT_rating.php");
        ?>
        <?php echo "( {$rating} / 5 )"; ?>
    </p>

    <?php 
        if ($feedback['comment'] != null) {
            echo "<p> Comment: ";
            echo $feedback['comment'];
            echo "</p>";
        }
    ?>
</div>