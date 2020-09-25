<?php
require('connection.php');
require('functions.php');
if (isset($_POST['submit'])) {
    $username = get_safe_value($con, $_POST['username']);
    $password = get_safe_value($con, $_POST['password']);
    $sql = "select * from ecommerce_admin_users where username = '$username' and password = '$password';";
    $res = mysqli_query($con, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
        session_start();
        $_SESSION['ADMIN_LOGIN'] = "yes";
        $_SESSION['ADMIN_USERNAME'] = $username;
        header("Location:categories.php");
        die();
    } else {
        echo "Invalid Details";
    }
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

    <div class="container">
        <div class="jumbotron text-center">
            <h1>
                Login
            </h1>
        </div>
        <form method="Post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" id="username" aria-describedby="usernameHelp" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>
            <div class="form-group form-check">
                <input type="submit" name='submit' class="btn btn-primary">
            </div>
        </form>
    </div>


</body>

</html>