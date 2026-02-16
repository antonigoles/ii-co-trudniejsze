<?php
namespace App;

class Session 
{
    public static function start_session() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function kill_session()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
?>