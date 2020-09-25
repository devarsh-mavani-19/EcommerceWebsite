<?php
require("connection.php");
require("functions.php");
session_start();
if (isset($_SESSION['ADMIN_LOGIN'])) {
    if (isset($_POST['submit'])) {
        $name = get_safe_value($con, $_POST['name']);


        if (isset($_POST['status'])) {
            $status = $_POST['status'];
            if ($status == "Deactive") {
                $status = 0;
            } else {
                $status = 1;
            }
            if (isset($_GET['id'])) {
                //update
                $m_id = $_GET['id'];
                if (($_FILES['image']['name'] != '')) {
                    $image = rand(1111111111, 9999999999) . '_' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../media/categories/' . $image);
                    $update_sql = "update ecommerce_category set status = '$status', name='$name', image='$image' where id='$m_id'";
                } else {
                    $update_sql = "update ecommerce_category set status = '$status', name='$name' where id='$m_id'";
                }
                $res = mysqli_query($con, $update_sql);
                header('location:categories.php');
                die();
            }
        } else {
            echo "Please select a status";
            die();
        }


        $select_sql = "select name from ecommerce_category where name = '$name'";
        $res = mysqli_query($con, $select_sql);

        if (mysqli_error($con)) {
            echo "failed to execute";
            die();
        }
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
                move_uploaded_file($_FILES['image']['tmp_name'], '../media/categories/' . $image);
                $insert_sql = "insert into ecommerce_category(name, status, image) values('$name', '$status', '$image')";
                $res = mysqli_query($con, $insert_sql);
            } else {
                echo "Please select a category";
                die();
            }


            if (mysqli_error($con)) {
                echo "failed to add new category " . mysqli_error($con);
            } else {
                header("location:categories.php");
                echo "Record Inserted";
                die();
            }
        }
    }
    if (isset($_GET['type'])) {
        $type = get_safe_value($con, $_GET['type']);
        if ($type == "update") {
            $m_id = get_safe_value($con, $_GET['id']);
            $select_sql = "select name from ecommerce_category where id = '$m_id'";
            $res = mysqli_query($con, $select_sql);
            if (mysqli_error($con)) {
                echo "<script>alert('error occured');</script>";
            } else if (mysqli_num_rows($res) > 0) {
                $row = mysqli_fetch_assoc($res);
                $m_name = $row['name'];
            } else {
                echo "<script>alert('no record found');</script>";
            }
        }
    }
} else {
    header('location:login.php');
    die();
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
        <form method="POST" class="m-5" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" required class="form-control" name="name" value="<?php echo (isset($m_name) ? $m_name : ""); ?>" id="category_name" aria-describedby="category_name_help">
            </div>
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile" accept="image/*" name="image" placeholder="image" />
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <div class="form-group form-radio">
                <input type="radio" required class="form-radio-input" id="staus-activate" name='status' value='Active'>
                <label class="form-radio-label" for="staus-activate">Active</label>
                <input type="radio" required class="form-radio-input" id="staus-deactivate" name="status" value='Deactive'>
                <label class="form-radio-label" for="staus-deactivate">Deactive</label>
            </div>

            <input type="submit" name='submit' class="btn btn-primary" />
        </form>
    </div>




    </form>
</body>

</html>