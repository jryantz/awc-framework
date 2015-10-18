<?php

class Token {
    
    /*
     * Generate function:
     *
     * create the token,
     * sets the token to session variable.
     *
     */
    public function generate() {
        return $_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }
    
    /*
     * Check function:
     *
     * checks to see if token is set to a session variable,
     * if yes, verify it with the provided token,
     * then unset the session,
     * return true.
     *
     */
    public function check($token) {
        if(isset($_SESSION['token']) && $token === $_SESSION['token'])  {
            unset($_SESSION['token']);
            return true;
        }
        return false;
    }
    
}

?>