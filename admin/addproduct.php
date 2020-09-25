<?php
require("connection.php");
require("functions.php");
session_start();
$m_name = '';
$m_category_id = '';
$m_mrp = '';
$m_price = '';
$m_qty = '';
$m_image = '';
$m_short_description = '';
$m_description = '';
$m_meta_title = '';
$m_meta_desc = '';
$m_meta_keyword = '';
$m_description = '';
if (isset($_SESSION['ADMIN_LOGIN'])) {
    if (isset($_POST['submit'])) {
        $name = get_safe_value($con, $_POST['name']);
        $category_id = get_safe_value($con, $_POST['category_id']);
        $mrp = get_safe_value($con, $_POST['mrp']);
        $price = get_safe_value($con, $_POST['price']);
        $qty = get_safe_value($con, $_POST['qty']);
        $short_description = get_safe_value($con, $_POST['short_description']);
        $description = get_safe_value($con, $_POST['description']);
        $meta_title = get_safe_value($con, $_POST['meta_title']);
        $meta_desc = get_safe_value($con, $_POST['meta_desc']);
        $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);

        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            if ($status == "Deactive") {
                $status = 0;
            } else {
                $status = 1;
            }
            if (isset($_GET['id'])) {
                //update
                if (($_FILES['image']['name'] != '')) {
                    $image = rand(1111111111, 9999999999) . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../media/products/' . $image);
                    $m_id = $_GET['id'];
                    $update_sql = "update ecommerce_product set status = '$status', image='$image',  product_name='$name', category_id = '$category_id', product_mrp='$mrp', product_price='$price', qty='$qty', short_description='$short_description', description='$description', meta_title='$meta_title', meta_desc='$meta_desc', meta_keyword = '$meta_keyword'   where id='$m_id'";
                    $res = mysqli_query($con, $update_sql);
                } else {
                    $m_id = $_GET['id'];
                    $update_sql = "update ecommerce_product set status = '$status', product_name='$name', category_id = '$category_id', product_mrp='$mrp', product_price='$price', qty='$qty', short_description='$short_description', description='$description', meta_title='$meta_title', meta_desc='$meta_desc', meta_keyword = '$meta_keyword'   where id='$m_id'";
                    $res = mysqli_query($con, $update_sql);
                }
                header('location:products.php');
            }
        } else {
            echo "Please select a status";
            die();
        }

        $select_sql = "select product_name from ecommerce_product where product_name = '$name'";
        $res = mysqli_query($con, $select_sql);
        if (mysqli_error($con)) {
            echo "failed to execute";
        } else {
            if (mysqli_num_rows($res) > 0) {
                //category already exist
                echo "<script>alert('category already exist');</script>";
            } else {
                if (isset($_POST['status'])) {
                    $status = $_POST['status'];
                    if ($status == "Deactive") {
                        $status = 0;
                    } else {
                        $status = 1;
                    }

                    //insert
                    $image = rand(1111111111, 9999999999) . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../media/products/' . $image);
                    $insert_sql = "insert into ecommerce_product (category_id, product_name, product_mrp, product_price, qty, image, short_description, description,  meta_title, meta_desc, meta_keyword, status) values('$category_id', '$name', '$mrp', '$price', '$qty', '$image', '$short_description', '$description', '$meta_title', '$meta_desc','$meta_keyword', '$status' );";
                    $res = mysqli_query($con, $insert_sql);
                } else {
                    echo "Please select a category";
                    die();
                }


                if (mysqli_error($con)) {
                    echo "failed to add new category " . mysqli_error($con);
                } else {
                    header("location:products.php");
                }
            }
        }
    }
    if (isset($_GET['type'])) {
        $type = get_safe_value($con, $_GET['type']);
        if ($type == "update") {
            $m_id = get_safe_value($con, $_GET['id']);
            $select_sql = "select * from ecommerce_product where id = '$m_id'";
            $res = mysqli_query($con, $select_sql);
            if (mysqli_error($con)) {
                echo "<script>alert('error occured');</script>";
            } else if (mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                $r_name = $row['product_name'];
                $r_category_id = $row['category_id'];
                $r_mrp = $row['product_mrp'];
                $r_price = $row['product_price'];
                $r_qty = $row['qty'];
                $r_short_desc = $row['short_description'];
                $r_description = $row['description'];
                $r_meta_title = $row['meta_title'];
                $r_meta_desc = $row['meta_desc'];
                $r_meta_keyword = $row['meta_keyword'];
            } else {
                echo "<script>alert('no record found');</script>";
            }
        }
    }
} else {
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <?php require('nav.php') ?>

    <div class="container">
        <div class='row'>
            <div class='col-12'>
                <form method="POST" class='m-5' enctype='multipart/form-data'>
                    <div class='form-group'>

                        <select name="category_id" class="custom-select">
                            <option>Select Category</option>
                            <?php
                            $cat_select = "select * from ecommerce_category";
                            $m_res = mysqli_query($con, $cat_select);
                            if (mysqli_error($con)) {
                                echo "<script>alert('failed to load categories');</script>";
                            } else {
                                while ($row = mysqli_fetch_assoc($m_res)) {
                                    $name = $row['name'];
                                    $n_id = $row['id'];
                                    echo "<option " . ($r_category_id == $n_id ? "selected='selected'" : "") . " value='$n_id'>" . $name . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">product name</label>
                        <input type="text" name="name" id='name' class="form-control" placeholder="name" value="<?php echo (isset($r_name) ? $r_name : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="name">MRP</label>
                        <input type="text" name="mrp" class="form-control" placeholder="mrp" value="<?php echo (isset($r_mrp) ? $r_mrp : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="Price">Price</label>
                        <input type="text" id='Price' name="price" class="form-control" placeholder="price" value="<?php echo (isset($r_price) ? $r_price : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="Quantity">Quantity</label>
                        <input type="text" class='form-control' name="qty" placeholder="qty" value="<?php echo (isset($r_qty) ? $r_qty : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" accept="image/*" name="image" placeholder="image" value="<?php echo (isset($r_image) ? $r_image : ""); ?>" />
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="short_desc">Short description</label>
                        <input type="text" id='short_desc' name="short_description" class='form-control' placeholder="short_desc" value="<?php echo (isset($r_short_desc) ? $r_short_desc : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" name="description" class='form-control' id='description' placeholder="desc" value="<?php echo (isset($r_description) ? $r_description : ""); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" class="form-control" id='meta_title' name="meta_title" placeholder="meta_title" value="<?php echo (isset($r_meta_title) ? $r_meta_title : ""); ?>" />

                    </div>
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <input type="text" name="meta_desc" id='meta_description' class="form-control" placeholder="meta_desc" value="<?php echo (isset($r_meta_desc) ? $r_meta_desc : ""); ?>" />

                    </div>
                    <div class="form-group">
                        <label for="meta_keyword">Meta Keywords</label>
                        <input type="text" name="meta_keyword" id='meta_keyword' class="form-control" placeholder="meta_kwd" value="<?php echo (isset($r_meta_keyword) ? $r_meta_keyword : ""); ?>" />

                    </div>
                    <div class="form-group form-radio">
                        <input type="radio" required class='form-radio-input' id='status-active' name="status" value="Active" />
                        <label class="form-radio-label" for="status-active">Active</label>
                        <input type="radio" name="status" class='form-radio-input' id='status-deactive' value="Deactive" />
                        <label class="form-radio-label" for="status-deactive">Deactive</label>
                    </div>

                    <input type="submit" name="submit" class='btn btn-success' />
                </form>
            </div>
        </div>
    </div>

</body>

</html>