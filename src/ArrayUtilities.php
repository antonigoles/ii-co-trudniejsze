<?php

namespace App;

class ArrayUtilities {

    /**
     * Returns `true` if all array elements pass the predicate
     * @param array $array
     * @param callable $predicate
     * @return void
     */
    public static function all(array $array, callable $predicate): bool 
    {
        foreach ($array as $element) {
            if (!$predicate($element)) {
                return false;
            } 
        }

        return true;
    }

    public static function has_intersection(array $a, array $b): bool
    {
        return !empty(array_intersect($a, $b));
    }

    public static function group_by(string $key, array $array): array
    {
        $grouped = [];
        foreach ($array as $element) {
            $grouped[$element[$key]][] = $element; 
        }
        return $grouped;
    }
}

?>