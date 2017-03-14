<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

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
        
        Updates
        
        <br><br>Framework
        
        <?php
        $data = file_get_contents('app/package.json');
        $json = json_decode($data, true);
        
        $update = file_get_contents($json['update-link'] . '/update.json');
        $updatetext = json_decode($update, true);
        
        echo '<br>Installed Version: ' . $json['version'];
        
        if(isset($updatetext['version'])) {
            echo '<br>You can update to version ' . $updatetext['version'] . ' automatically by clicking the button below.';
            echo '<br><a href="#">Update</a> &nbsp; <a href="#">Download ' . $updatetext['version'] . '</a>';
        } else {
            echo 'Error with update system.';
        }
        ?>
        
        <br><br>Packages
    </body>
</html>