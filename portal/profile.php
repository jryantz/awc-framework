<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['updateName'])) {
    $User = new User_Name;
    $User->changeName();
}

if(isset($_POST['submitName'])) {
    $User = new User_Name;
    $User->createName();
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
        
        if(isset($data[1]['first']) && isset($data[1]['middle']) && isset($data[1]['last'])):
        echo $data[1]['first'] . ' ' . $data[1]['middle'] . ' ' . $data[1]['last'];
        ?>
        
        <form action="" method="post">
            <input type="text" name="first" placeholder="First Name">
            <input type="text" name="middle" placeholder="Middle Name / MI">
            <input type="text" name="last" placeholder="Last Name">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="updateName" value="Update">
        </form>
        <?php else: ?>
        <form action="" method="post">
            <input type="text" name="first" placeholder="First Name">
            <input type="text" name="middle" placeholder="Middle Name / MI">
            <input type="text" name="last" placeholder="Last Name">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="submitName" value="Submit">
        </form>
        
        <?php endif; ?>
        
        <br>
        <a href="#">Change Password</a>
    </body>
</html>