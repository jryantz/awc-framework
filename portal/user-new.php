<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['createUser'])) {
    $User = new Admin;
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
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="checkbox" name="random" id="random"><label for="random">Generate Random Password</label>
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
    <script>
        function passView() {

            if(document.getElementById('password').attributes['type'].value == 'password') {
                document.getElementById('password').attributes['type'].value = 'text';
            } else {

                document.getElementById('password').attributes['type'].value = 'password';
            }
        }

        function randomPassword() {

            var result = '';
            var length = 12;
            var options = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            for(var i = 0; i <= length; i++) {
                result += options[Math.round(Math.random() * (options.length - 1))];
            }

            return result;
        }

        document.getElementById('random').addEventListener('click', function() {

            if(document.getElementById('random').checked) {
                document.getElementById('password').attributes['type'].value = 'text';
                document.getElementById('password').disabled = true;
                document.getElementById('password').value = randomPassword();

            } else {

                document.getElementById('password').attributes['type'].value = 'password';
                document.getElementById('password').disabled = false;
                document.getElementById('password').value = '';
            }
        });
    </script>
</html>
