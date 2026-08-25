<?php
$con = mysqli_connect("localhost", "root", "", "Craftzon");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['order_id'], $_POST['uid'], $_POST['rating'], $_POST['comment'])) {
    $order_id = intval($_POST['order_id']);
    $uid = intval($_POST['uid']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($con, $_POST['comment']);

    // Get user name from order
    $res = mysqli_query($con, "SELECT fullname FROM craftorder WHERE orderid=$order_id AND uid=$uid LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $user_name = $row['fullname'];

        $insert = "INSERT INTO feedbacks (order_id, user_name, rating, comment) 
                   VALUES ($order_id, '$user_name', $rating, '$comment')";
        if (mysqli_query($con, $insert)) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => mysqli_error($con)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Order not found']);
    }
}
mysqli_close($con);
?>
