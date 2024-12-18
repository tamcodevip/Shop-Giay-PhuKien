<?php
// Đảm bảo kết nối cơ sở dữ liệu
include("../db/dbconn.php");

// Lấy danh sách sản phẩm
$query = $conn->query("SELECT * FROM `product` WHERE category='feature' ORDER BY product_id DESC") or die(mysqli_error());

// Thêm nút "Edit" vào bảng sản phẩm
while ($fetch = $query->fetch_array()) {
    $id = $fetch['product_id'];
?>
<tr class="del<?php echo $id ?>">
    <td><img class="img-polaroid" src="../photo/<?php echo $fetch['product_image'] ?>" height="70px" width="80px"></td>
    <td><?php echo $fetch['product_name'] ?></td>
    <td><?php echo $fetch['product_price'] ?></td>
    <td><?php echo $fetch['product_size'] ?></td>

    <?php
    $query1 = $conn->query("SELECT * FROM `stock` WHERE product_id='$id'") or die(mysqli_error());
    $fetch1 = $query1->fetch_array();
    ?>

    <td><?php echo $fetch1['qty'] ?></td>
    <td>
        <!-- Nút Edit -->
        <a href="#edit<?php echo $id; ?>" role="button" class="btn btn-warning" data-toggle="modal">
            <i class="icon-edit icon-white"></i> Edit
        </a>
    </td>
</tr>

<!-- Modal chỉnh sửa sản phẩm -->
<div id="edit<?php echo $id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" style="width:400px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="editModalLabel">Edit Product</h3>
    </div>
    <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
            <center>
                <table>
                    <tr>
                        <td><input type="file" name="product_image"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="product_name" value="<?php echo $fetch['product_name']; ?>" style="width:250px;" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="product_price" value="<?php echo $fetch['product_price']; ?>" style="width:250px;" required></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="product_size" value="<?php echo $fetch['product_size']; ?>" style="width:250px;" required></td>
                    </tr>
                </table>
            </center>
    </div>
    <div class="modal-footer">
        <input class="btn btn-primary" type="submit" name="edit<?php echo $id; ?>" value="Save Changes">
        <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
        </form>
    </div>
</div>

<?php
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Duyệt qua từng sản phẩm và kiểm tra nút chỉnh sửa được nhấn
    $query = $conn->query("SELECT * FROM `product` WHERE category='feature'") or die(mysqli_error());
    while ($fetch = $query->fetch_array()) {
        $product_id = $fetch['product_id'];

        if (isset($_POST['edit' . $product_id])) {
            $product_name = $_POST['product_name'];
            $product_price = $_POST['product_price'];
            $product_size = $_POST['product_size'];

            // Kiểm tra và xử lý cập nhật hình ảnh
            if (!empty($_FILES['product_image']['name'])) {
                $name = rand(0, 98987787866533499) . $_FILES['product_image']['name'];
                $temp = $_FILES['product_image']['tmp_name'];

                move_uploaded_file($temp, "../photo/$name");

                $conn->query("UPDATE product SET product_image = '$name' WHERE product_id = '$product_id'") or die(mysqli_error());
            }

            // Cập nhật các thông tin khác
            $conn->query("UPDATE product SET product_name = '$product_name', product_price = '$product_price', product_size = '$product_size' WHERE product_id = '$product_id'") or die(mysqli_error());

            echo "<script>window.location = 'admin_feature.php';</script>";
            exit; // Đảm bảo chỉ xử lý một sản phẩm
        }
    }
}
?>
