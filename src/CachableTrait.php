<?php

namespace App;

trait CachableTrait {
    public static function try_read_from_cache(string $key, string $container = 'default'): mixed
    {
        Session::start_session();
        if (!isset($_SESSION[$container])) return null;
        $cache = json_decode($_SESSION[$container], true);
        return $cache[$key] ?? null;
    }

    public static function write_cache(string $key, mixed $value, string $container = 'default'): void 
    {
        Session::start_session();
        $cache = [];
        if (isset($_SESSION[$container])) {
            $cache = json_decode($_SESSION[$container], true);
        }
        $cache[$key] = $value;
        $_SESSION[$container] = json_encode($cache);
    }
}

?>