<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['updateName'])) {
    $User = new User;
    $User->changeName();
}

$Token = new Token;
$token = $Token->generate();

?>

<!doctype html>

<html>
    <head>

    </head>

    <body>
        <?php
        if(isset($_SESSION['alert'])) {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']);
        }

        include_once('includes/menu.inc.php');

        $User = new User;
        $data = $User->getData();

        echo '<input type="text" name="username" value="' . $data[0]['username'] . '" disabled>Usernames cannot be changed.<br><br>';

        echo $data[0]['first'] . ' ' . $data[0]['middle'] . ' ' . $data[0]['last'];
        ?>

        <form action="" method="post">
            <input type="text" name="first" placeholder="First Name">
            <input type="text" name="middle" placeholder="Middle Name / MI">
            <input type="text" name="last" placeholder="Last Name">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="updateName" value="Update">
        </form>

        <br>
        <a href="#">Change Password</a>
    </body>
</html>
