<?php

class Verify {
    
    // checks length
    public function length($content, $size) {
        if(strlen($content) <= $size) {
            return true;
        }
        return false;
    }
    
    // check similarity
    public function same($content, $content2) {
        if($content == $content2) {
            return true;
        }
        return false;
    }
    
    // 000-000-0000
    public function phone($content) {
        if(preg_match('/^\b[0-9]{3}-[0-9]{3}-[0-9]{4}\b$/', $content)) {
            return true;
        }
        return false;
    }
    
    // user@website.ext
    public function email($content) {
        if(filter_var($content, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    // ...,000,000,000.00
    public function price($content) {
        if(preg_match('/^\b\d{1,3}(\,\d{3})*\.\d{2}\b$/', $content)) {
            return true;
        }
        return false;
    }
    
}

?>