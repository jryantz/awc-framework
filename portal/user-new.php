<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['createUser'])) {
    $User = new User_Admin;
    $User->create();
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
        
        <a href="#" onclick="passView();">Show/Hide Password</a>
        
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username">
            <input type="text" name="email" placeholder="Email">
            <input type="password" name="password" id="pass" placeholder="Password">
            <select name="rank">
                <option value="" disabled selected>Select Rank</option>
                <option value="0">Subscriber</option>
                <option value="1">User</option>
                <option value="2">Moderator</option>
                <option value="3">Manager</option>
                <option value="4">Admin</option>
                <option value="9">Site Admin</option>
            </select>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="submit" name="createUser" value="Create">
        </form>
    </body>
    
    <script src="js/passView.js"></script>
</html>