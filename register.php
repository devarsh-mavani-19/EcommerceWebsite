<?php
require('connection.php');
require('functions.php');

if (isset($_POST['submit'])) {
    $name = get_safe_value($con, $_POST['username']);
    $email = get_safe_value($con, $_POST['email']);
    $password = get_safe_value($con, $_POST['password']);
    $confirm_password =  get_safe_value($con, $_POST['confirm_password']);
    if ($password == $confirm_password) {
        $select_sql = "select email from users where email = '$email';";
        $res = mysqli_query($con, $select_sql);
        if (mysqli_error($con)) {
            echo "Failed to make account " . mysqli_error($con);
        } else {
            $no_of_rows = mysqli_num_rows($res);
            if ($no_of_rows > 0) {
                echo "account already exist ";
            } else {
                $password = hash('sha512', $password);
                $insert_sql = "insert into users(username, email, password) values('$name', '$email', '$password');";
                $res = mysqli_query($con, $insert_sql);
                if (mysqli_errno($con)) {
                    echo "Failed to create account ";
                } else {
                    //account created
                    header('location:login.php');
                }
            }
        }
    } else {
        echo "passwords do not match";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <div class='container'>
        <div class="jumbotron text-center">
            <h2>
                Register
            </h2>
        </div>
        <form method="POST">

            <input type="text" class="form-control m-5" name="username" placeholder="Username" />
            <input type="email" class="form-control m-5" name="email" placeholder="email" />
            <input type="password" class="form-control m-5" name="password" placeholder="password" />
            <input type="password" class='form-control m-5' name="confirm_password" placeholder="retype password" />
            <input type="submit" class="btn btn-success m-5" name='submit' />
        </form>
    </div>
</body>

</html>