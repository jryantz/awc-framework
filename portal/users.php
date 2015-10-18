<?php

require_once('app/init.php');

if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

if(isset($_POST['deleteUser'])) {
    $User = new User;
    if($_POST['confirm'] == 1) {
        $User->delete();
    } else {
        $_SESSION['alert'] = 'Please confirm that you want to delete before clicking the delete button.';
    }
}

if(isset($_POST['updateRank'])) {
    $User = new User;
    $User->changeRank();
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
        
        <table border="1">
            <tr>
                <td>Username</td>
                <td>Email</td>
                <td>Create Date</td>
                <td>Rank</td>
                <td></td>
                <td>Delete</td>
                <td>Change Rank</td>
            </tr>
        
            <?php
            $User = new User;
            $list = $User->listAll();

            foreach($list as $item) {
                echo '<tr>';
                
                echo '<td>' . $item['username'] . '</td>';
                echo '<td>' . $item['email'] . '</td>';
                echo '<td>' . $item['create_date'] . '</td>';
                
                echo '<td>';
                switch($item['rank']) {
                    case 0:
                        echo 'Subscriber';
                        break;
                    case 1:
                        echo 'User';
                        break;
                    case 2:
                        echo 'Moderator';
                        break;
                    case 3:
                        echo 'Manager';
                        break;
                    case 4:
                        echo 'Admin';
                        break;
                    case 9:
                        echo 'Site Admin';
                        break;
                    default:
                        break;
                }
                echo '</td>';
                
                echo '<td></td>';
                
                if($_SESSION['user'] == $item['id']) {
                    echo '<td></td><td></td>';
                } else {
                    
                    echo '<td>';
                        echo '<form action="" method="post">';
                            echo 'Confirm: ';
                            echo '<input type="hidden" name="id" value="' . $item['id'] . '">';
                            echo '<input type="hidden" name="confirm" value="0">';
                            echo '<input type="checkbox" name="confirm" value="1">';
                            echo '<input type="submit" name="deleteUser" value="X">';
                        echo '</form>';
                    echo '</td>';

                    echo '<td>';
                        echo '<form action="" method="post">';
                            echo '<input type="hidden" name="id" value="' . $item['id'] . '">';
                            echo '<select name="rank">';
                                echo '<option value="" disabled selected>Select Rank</option>';
                                echo '<option value="0">Subscriber</option>';
                                echo '<option value="1">User</option>';
                                echo '<option value="2">Moderator</option>';
                                echo '<option value="3">Manager</option>';
                                echo '<option value="4">Admin</option>';
                                echo '<option value="9">Site Admin</option>';
                            echo '</select>';
                            echo '<input type="submit" name="updateRank" value="Update">';
                        echo '</form>';
                    echo '</td>';
                }
                
                echo '</tr>';
            }
            ?>
        </table>
    </body>
</html>