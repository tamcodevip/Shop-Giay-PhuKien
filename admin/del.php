<?php
include("../db/dbconn.php");

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $query = $conn->query("SELECT product_image FROM product WHERE product_id = '$product_id'") or die(mysqli_error($conn));
    $result = $query->fetch_array();
    $image_path = "../photo/" . $result['product_image'];

    if (file_exists($image_path)) {
        unlink($image_path);
    }

    $conn->query("DELETE FROM product WHERE product_id = '$product_id'") or die(mysqli_error($conn));

    $conn->query("DELETE FROM stock WHERE product_id = '$product_id'") or die(mysqli_error($conn));

    echo "<script>alert('Sản phẩm đã được xóa thành công!'); window.location = 'admin_feature.php';</script>";
} else {
    echo "<script>alert('Không tìm thấy sản phẩm để xóa!'); window.location = 'admin_feature.php';</script>";
}
?>
