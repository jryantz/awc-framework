<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['submitName'])) {
    $User = new User;
    $User->createName();
}

if(isset($_POST['updateName'])) {
    $User = new User;
    $User->updateName();
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
        ?>
        
        <form action="" method="post">
            <input type="text" name="first" placeholder="First Name">
            <input type="text" name="middle" placeholder="Middle Name / MI">
            <input type="text" name="last" placeholder="Last Name">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="submitName" value="Submit">
        </form>
        
        <form action="" method="post">
            <input type="text" name="first" placeholder="First Name">
            <input type="text" name="middle" placeholder="Middle Name / MI">
            <input type="text" name="last" placeholder="Last Name">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="updateName" value="Update">
        </form>
    </body>
</html>