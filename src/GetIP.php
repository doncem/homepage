<?php

/**
 * Simple user IP retrieval class
 */
abstract class GetIP {

    /**
     * Retrieve IP
     * @return string
     */
    public static function ip() {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        } else {
            $ip = "undefined";
        }
        
        return $ip;
    }
}
