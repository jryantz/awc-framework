<?php

class User_Admin {
    
    /*
     * Create function: 
     *
     * verifies the token, 
     * gets the information submitted from the form,
     * verifies that the information follows the parameters set,
     * connect to the database, 
     * verify that the user being created doesn't already exist,
     * generate server side information,
     * insert it into the database.
     *
     */
    public function create() {
        
        $Token = new Token;
        if(!$Token->check($_POST['token'])) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {
            
            $Verify = new Verify;
            
            $username = trim(strip_tags($_POST['username']));
            $email = trim(strip_tags($_POST['email']));
            $password = trim(strip_tags($_POST['password']));
            $rank = $_POST['rank'];
            
            $email = explode('@', $email);
            
            if(!isset($username) && !isset($email) && !isset($password) && !isset($rank)) {
                $_SESSION['alert'] = 'Not all fields have been completed.';
            } elseif(!$Verify->length($username, 255)) {
                $_SESSION['alert'] = 'The username is too long.';
            } elseif(!$Verify->length($email[0], 255)) {
                $_SESSION['alert'] = 'The email entered is too long.';
            } elseif(!$Verify->length($email[1], 255)) {
                $_SESSION['alert'] = 'The email entered is too long.';
            } else {
                
                $Db = new Db;
                $query = $Db->query('user', array(array('username', '=', $username, '')));
                $numrows = mysqli_num_rows($query);

                if($numrows > 0) {
                    $_SESSION['alert'] = 'Error, please try again.';
                } else {
                    
                    $salt = base64_encode(mcrypt_create_iv(128, MCRYPT_DEV_URANDOM));
                    $crypt = hash('sha512', $username . $salt . $password);
                    
                    $datetime = date('Y-m-d H:i:s');
                    
                    $insert = $Db->insert('user', array('', $username, $crypt, $email[0], $email[1], $salt, $datetime, $rank));
                    
                    if(!$insert) {
                        $_SESSION['alert'] = 'User could not be created.';
                    } else {
                        
                        $_SESSION['alert'] = 'The user "' . $username . '" was created.';
                    }
                }
            } 
        }
    }
    
    /*
     * Delete function:
     *
     * gets the posted id,
     * check to make sure the id only cooresponds to one user,
     * delete the user,
     * make sure the delete worked.
     *
     */
    public function delete() {
        
        $id = $_POST['id'];
        
        if(is_numeric($id)) {
            $Db = new Db;
            $query = $Db->query('user', array(array('id', '=', $id, '')));
            $numrows = mysqli_num_rows($query);

            if($numrows != 1) {
                $_SESSION['alert'] = 'Error, please try again.';
            } else {

                $delete = $Db->delete('user', array(array('id', '=', $id, '')));

                if(!$delete) {
                    $_SESSION['alert'] = 'User account could not be deleted.';
                } else {

                    $_SESSION['alert'] = 'User deleted.';
                }
            }
        }
    }
    
    /*
     * ChangeRank function:
     * 
     * get the posted data from the form,
     * check if the rank is a valid one,
     * if it is, check to see if the user exists and is valid to be updated,
     * update the users rank to the selected one.
     *
     */
    public function changeRank() {
        
        $id = $_POST['id'];
        $rank = $_POST['rank'];
        
        if(is_numeric($id) && is_numeric($rank)) {
            $valid = array(0, 1, 2, 3, 4, 9);

            if(in_array($rank, $valid)) {
                $Db = new Db;
                $query = $Db->query('user', array(array('id', '=', $id, '')));
                $numrows = mysqli_num_rows($query);

                if($numrows != 1) {
                    $_SESSION['alert'] = 'Error, please try again.';
                } else {

                    $update = $Db->update('user', array('rank', '=', $rank), array(array('id', '=', $id, '')));

                    if(!$update) {
                        $_SESSION['alert'] = 'User rank could not be updated.';
                    } else {

                        $_SESSION['alert'] = 'The users rank has been updated.';
                    }
                }
            }
        }
    }
    
}

?>