<?php

class User {
    
    /*
     * Login function:
     * 
     * gets post information from a submitted form,
     * checks and sanitizes entered data,
     * gets hashed password and salt from the database based on username entered,
     * hashes the entered password with entered data and the salt from the db,
     * logs user in and sets session with user id from database.
     *
     */
    public function login() {
        
        $Token = new Token;
        if(!$Token->check($_POST['token'])) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {
            
            $username = trim(strip_tags($_POST['username']));
            $password = trim(strip_tags($_POST['password']));
            
            if(!isset($username) && !isset($password)) {
                $_SESSION['alert'] = 'Not all fields have been completed.';
            } else {

                $Db = new Db;
                $query = $Db->query('user', array(array('username', '=', $username, '')));
                $numrows = mysqli_num_rows($query);

                if($numrows != 1) {
                    $_SESSION['alert'] = 'Error, please try again.';
                } else {

                    while($row = mysqli_fetch_assoc($query)) {
                        $Dbid = $row['id'];
                        $Dbpassword = $row['password'];
                        $Dbsalt = $row['salt'];
                    }
                    $crypt = hash('sha512', $username . $Dbsalt . $password);

                    if($crypt != $Dbpassword) {
                        $_SESSION['alert'] = 'Error, please try again.';
                    } else {
                        
                        $_SESSION['user'] = $Dbid;
                        header('Location: index.php');
                    }
                }
            }
        }
    }
    
    /*
     * Register function:     
     * 
     * gets post information from a submitted form,
     * checks and sanitizes entered data,
     * checks db to verify there is no pre-registered user with the provided username,
     * generates a salt,
     * hashes password,
     * grabs current date and time,
     * inserts data into database and forces a redirect to index which will force to login because user is not logged in.
     * 
     */
    public function register() {
        
        $Token = new Token;
        if(!$Token->check($_POST['token'])) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {
            
            $Verify = new Verify;
            
            $username = trim(strip_tags($_POST['username']));
            $password = trim(strip_tags($_POST['password']));
            $repassword = trim(strip_tags($_POST['repassword']));
            $email = trim(strip_tags($_POST['email']));
            
            $email = explode('@', $email);
            
            if(!isset($username) && !isset($password) && !isset($repassword) && !isset($email)) {
                $_SESSION['alert'] = 'Not all fields have been completed.';
            } elseif(!$Verify->length($username, 255)) {
                $_SESSION['alert'] = 'The username is too long.';
            } elseif(!$Verify->same($password, $repassword)) {
                $_SESSION['alert'] = 'The passwords entered are not the same.';
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
                    $rank = 0;
                    
                    $insert = $Db->insert('user', array('', $username, $crypt, $email[0], $email[1], $salt, $datetime, $rank));
                    
                    if(!$insert) {
                        $_SESSION['alert'] = 'User could not be registered.';
                    } else {
                        
                        $_SESSION['alert'] = 'Successfully registered, you can now login with your credentials.';
                        header('Location: login.php');
                    }
                }
            } 
        }
    }
    
    /*
     * Logout function:
     *
     * destroys the session,
     * redirects to index which forces to login page because user is not logged in.
     *
     */
    public function logout() {
        
        session_destroy();
        header('Location: index.php');
    }
    
    /*
     * GetData function:
     *
     * connect to the database, 
     * check to see if the current user is in the database (should be),
     * if so, get the username, email, first, middle, and last name,
     * return all of the information in an array.
     *
     */
    public function getData() {
        
        $Db = new Db;
        $query = $Db->query('user', array(array('id', '=', $_SESSION['user'], '')));
        $numrows = mysqli_num_rows($query);

        if($numrows != 1) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {

            $total = array();
            
            while($row = mysqli_fetch_assoc($query)) {
                $total[] = array(
                    'username' => $row['username'],
                    'email' => $row['email_local'] . '@' . $row['email_domain'],
                );
            }
            
            $query = $Db->query('user_name', array(array('user_id', '=', $_SESSION['user'], '')));
            $numrows = mysqli_num_rows($query);
            
            if($numrows == 1) {
                while($row = mysqli_fetch_assoc($query)) {
                    $total[] = array(
                        'first' => $row['first'],
                        'middle' => $row['middle'],
                        'last' => $row['last']
                    );
                }
            }
            
            return $total;
        }
        
        return;
    }
    
    /*
     * GetRank function:
     *
     * connects to the database,
     * gets rank number from the db for the logged in user,
     * return the number.
     *
     */
    public function getRank() {
        
        $Db = new Db;
        $query = $Db->query('user', array(array('id', '=', $_SESSION['user'], '')));
        $numrows = mysqli_num_rows($query);

        if($numrows > 1) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {

            while($row = mysqli_fetch_assoc($query)) {
                return $row['rank'];
            }
        }
        
        return;
    }
    
    /*
     * ListAll function:
     *
     * connects to the database,
     * checks to make sure that atleast one user exists,
     * get all of the information required for each user,
     * return it in an array.
     *
     */
    public function listAll() {
        
        $Db = new Db;
        $query = $Db->query('user', array());
        $numrows = mysqli_num_rows($query);
        
        if($numrows < 1) {
            $_SESSION['alert'] = 'Error, please try again.';
        } else {
            
            $total = array();
            
            while($row = mysqli_fetch_assoc($query)) {
                $total[$row['id']][0] = array(
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'email' => $row['email_local'] . '@' . $row['email_domain'],
                    'create_date' => $row['create_date'],
                    'rank' => $row['rank']
                );
                
                $queryName = $Db->query('user_name', array(array('user_id', '=', $row['id'], '')));
                $numrowsName = mysqli_num_rows($queryName);

                if($numrowsName == 1) {
                    while($rowName = mysqli_fetch_assoc($queryName)) {
                        $total[$rowName['user_id']][1] = array(
                            'first' => $rowName['first'],
                            'middle' => $rowName['middle'],
                            'last' => $rowName['last']
                        );
                    }
                }
            }
            
            return $total;
        }
        
        return;
    }
    
}

?>