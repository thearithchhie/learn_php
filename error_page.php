<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
</head>
<body>
    <h1>An Error Occurred</h1>
    <?php
    if(isset($_SESSION['error_message'])) {
        echo "<p>Error: " . $_SESSION['error_message'] . "</p>";
        unset($_SESSION['error_message']);
    } else {
        echo "<p>No error message found in session.</p>";
    }
    ?>
    <a href="index.php">Back to Home</a>
</body>
</html>