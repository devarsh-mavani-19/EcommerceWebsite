<?php
require("connection.php");
require("functions.php");
if (isset($_POST['submit'])) {
    $email = get_safe_value($con, $_POST['email']);
    $password = hash('sha512', get_safe_value($con, $_POST['password']));
    $select_sql = "select username, email, password from users where email = '$email' and password='$password'";
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        $user_msg = 'Failed to login';
    } else {
        if (mysqli_num_rows($res) == 1) {
            $row = mysqli_fetch_assoc($res);
            $name = $row['username'];
            session_start();
            $_SESSION['username'] = $name;
            if (isset($_GET['ref']) && $_GET['ref'] != '') {
                $ref = $_GET['ref'];
                $id = (isset($_GET['id']) ? $_GET['id'] : '');
                if ($id == '') {
                    header('location:' . $ref);
                } else {
                    header('location:' . $ref . '?id=' . $id);
                }
            } else {
                header('location:index.php');
                die();
            }
        } else {
            $user_msg = 'Invalid Credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require('htmlcommon.php'); ?>
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
                <label for="email">Email address</label>
                <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>
            <div class="form-group form-check">
                <input type="submit" name='submit' class="btn btn-primary">
            </div>
        </form>
        <?php if (isset($user_msg) && $user_msg != '') {
        ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $user_msg; ?>
            </div>
        <?php } ?>
    </div>

</body>

</html>