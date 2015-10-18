<?php

require_once('app/init.php');

if(isset($_POST['loginSubmit'])) {
    $User = new User;
    $User->login();
}

if(isset($_POST['registerSubmit'])) {
    $User = new User;
    $User->register();
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
        ?>
        
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="loginSubmit" value="Login">
        </form>

        <form action="" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input type="password" name="repassword" placeholder="Repeat Password">
            <input type="text" name="email" placeholder="name@domain.com">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="registerSubmit" value="Register">
        </form>
    </body>
</html>