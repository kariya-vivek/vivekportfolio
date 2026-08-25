<?php
$con = mysqli_connect("localhost", "root", "", "Craftzon");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id']);
    $user_name = mysqli_real_escape_string($con, $_POST['user_name']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);

    $sql = "INSERT INTO feedbacks (order_id, user_name, rating, comment) 
            VALUES ('$order_id', '$user_name', '$rating', '$comment')";

    if (mysqli_query($con, $sql)) {
        header("Location: orders.php?success=1"); // redirect back to order page
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>
