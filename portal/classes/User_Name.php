<?php

class User_Name {
    
    /*
     * CreateName function:
     * 
     * get posted information,
     * verify the size is okay to fit in the db,
     * connect to the database, 
     * make sure there isn't a name already input for that user,
     * if not, insert the users name.
     *
     */
    public function createName() {
        
        $Verify = new Verify;
        
        $first = trim(strip_tags($_POST['first']));
        $middle = trim(strip_tags($_POST['middle']));
        $last = trim(strip_tags($_POST['last']));

        if(!$Verify->length($first, 255)) {
            $_SESSION['alert'] = 'Your first name is too long to store in the database.';
        } elseif(!$Verify->length($middle, 255)) {
            $_SESSION['alert'] = 'Your middle name is too long to store in the database.';
        } elseif(!$Verify->length($last, 255)) {
            $_SESSION['alert'] = 'Your last name is too long to store in the database.';
        } else {

            $Db = new Db;
            $query = $Db->query('user_name', array(array('user_id', '=', $_SESSION['user'], '')));
            $numrows = mysqli_num_rows($query);
            
            if($numrows > 0) {
                $_SESSION['alert'] = 'Error, please try again.';
            } else {
                
                $insert = $Db->insert('user_name', array('', $_SESSION['user'], $first, $middle, $last));
                
                if(!$insert) {
                    $_SESSION['alert'] = 'The name could not be entered in the database.';
                } else {
                    
                    $_SESSION['alert'] = 'Your name has successfully been entered.';
                }
            }
        }
    }
    
    /*
     * ChangeName function:
     *
     * get posted information,
     * verify the size is okay to fit in the db,
     * connect to the database, 
     * make sure there is a name inputted for that user,
     * then check if every field has been entered, if not fill it with the current data,
     * update the first, then middle, then last name.
     *
     */
    public function changeName() {
        
        $Verify = new Verify;
        
        $first = trim(strip_tags($_POST['first']));
        $middle = trim(strip_tags($_POST['middle']));
        $last = trim(strip_tags($_POST['last']));

        if(!$Verify->length($first, 255)) {
            $_SESSION['alert'] = 'Your first name is too long to store in the database.';
        } elseif(!$Verify->length($middle, 255)) {
            $_SESSION['alert'] = 'Your middle name is too long to store in the database.';
        } elseif(!$Verify->length($last, 255)) {
            $_SESSION['alert'] = 'Your last name is too long to store in the database.';
        } else {
            
            $Db = new Db;
            $query = $Db->query('user_name', array(array('user_id', '=', $_SESSION['user'], '')));
            $numrows = mysqli_num_rows($query);
            
            if($numrows != 1) {
                $_SESSION['alert'] = 'Error, please try again.';
            } else {
                
                while($row = mysqli_fetch_assoc($query)) {
                    if($first == '') {
                        $first = $row['first'];
                    }
                    
                    if($middle == '') {
                        $middle = $row['middle'];
                    }
                    
                    if($last == '') {
                        $last = $row['last'];
                    }
                }
                
                $updateFirst = $Db->update('user_name', array('first', '=', $first), array(array('user_id', '=', $_SESSION['user'], '')));
                
                if(!$updateFirst) {
                    $_SESSION['alert'] = 'Error, could not completely update name.';
                } else {
                    
                    $updateMiddle = $Db->update('user_name', array('middle', '=', $middle), array(array('user_id', '=', $_SESSION['user'], '')));
                    
                    if(!$updateMiddle) {
                        $_SESSION['alert'] = 'Error, could not completely update name.';
                    } else {
                        
                        $updateLast = $Db->update('user_name', array('last', '=', $last), array(array('user_id', '=', $_SESSION['user'], '')));
                        
                        if(!$updateLast) {
                            $_SESSION['alert'] = 'Error, could not completely update name.';
                        } else {
                            
                            $_SESSION['alert'] = 'Your name has been updated.';
                        }
                    }
                }
            }
        }
    }
    
}

?>