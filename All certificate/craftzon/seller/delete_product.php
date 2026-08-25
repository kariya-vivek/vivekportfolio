<?php



// delete_product.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];

    // DB connection
    $con = mysqli_connect("localhost", "root", "", "craftzon");
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Start transaction
    mysqli_begin_transaction($con);

    try {
        // Step 1: Delete dependent rows (except those with ON DELETE CASCADE)

        // advertisements -> productid
        $stmt = mysqli_prepare($con, "DELETE FROM advertisements WHERE productid = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // auction_table -> product_id
        $stmt = mysqli_prepare($con, "DELETE FROM auction_table WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // wishlist -> product_id
        $stmt = mysqli_prepare($con, "DELETE FROM wishlist WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // ❌ DO NOT delete from user_cart manually — it's handled by ON DELETE CASCADE

        // Step 2: Delete from product_table (parent)
        $stmt = mysqli_prepare($con, "DELETE FROM product_table WHERE product_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $productId);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($con);
            echo "<script>alert('✅ Product deleted successfully'); window.location.href=document.referrer;</script>";
        } else {
            mysqli_rollback($con);
            echo "<script>alert('⚠️ Product not found or already deleted'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('❌ Error deleting product: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('❌ Invalid request'); window.history.back();</script>";
}
?>