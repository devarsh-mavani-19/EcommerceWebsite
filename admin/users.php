<?php
require('connection.php');
require('functions.php');
session_start();
if (isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN'] != '') {
    $select_sql = "select * from users;";
    $res = mysqli_query($con, $select_sql);
    if (mysqli_error($con)) {
        die('error ' . mysqli_error($con));
    }
} else {
    header('login.php');
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <?php require('htmlcommon.php') ?>
</head>

<body>
    <?php require('nav.php') ?>
    <div class="container">
        <table class='table'>
            <thead>
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Username
                    </th>
                    <th>
                        Email
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($res)) {
                    $id = $row['id'];
                    $username = $row['username'];
                    $email = $row['email'];
                ?>
                    <tr>
                        <td>
                            <?php echo $id; ?>
                        </td>
                        <td>
                            <?php echo $username; ?>
                        </td>
                        <td>
                            <?php echo $email; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>