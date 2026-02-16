<?php
namespace App;

class Throttle 
{
    public static function for(int $seconds): bool 
    {
        Session::start_session();

        $last_throttle_call = $_SESSION['last_throttle_call'] ?? null;
        if (!$last_throttle_call) {
            return false;
        }

        if (time() - intval($last_throttle_call) >= $seconds) {
            return false;
        };

        $_SESSION['last_throttle_call'] = time();
        return true;
    }
}
?>