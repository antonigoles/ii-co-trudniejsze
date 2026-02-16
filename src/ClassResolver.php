<?php
namespace App;

class ClassResolver 
{
    public static function match_classes_from_usos_to_local(): array
    {
        $cache_hit = self::try_read_from_cache('resolved_classes');
        if ($cache_hit) {
            return $cache_hit;
        }

        $result = [];

        $classes = OAuth::fetch_user_course_names();

        $classes_normalized = array_map(
            static fn ($class) => trim(strtolower($class)), 
            $classes
        );

        $local_classes = array_values(json_decode(file_get_contents(__DIR__ . '/../class_list.json'), true));
        $local_classes = array_merge(...$local_classes);

        $local_classes_normalized = array_map(
            static fn ($class) => trim(strtolower($class)),
            $local_classes
        );

        for ($i = 0; $i < count($classes); $i++) {
            $class = $classes_normalized[$i];
            for ($j = 0; $j < count($local_classes); $j++) {
                $local_class = $local_classes_normalized[$j];
                if ($local_class == $class) {
                    $result[$classes[$i]] = $local_classes[$j];
                }
            }
        }

        self::write_cache('resolved_classes', $result);

        return $result;
    }

    public static function try_read_from_cache(string $key): mixed 
    {
        Session::start_session();
        if (!isset($_SESSION['class_resolver'])) return null;
        $cache = json_decode($_SESSION['class_resolver'], true);
        return $cache[$key] ?? null;
    }

    public static function write_cache(string $key, mixed $value): void 
    {
        Session::start_session();
        $cache = [];
        if (isset($_SESSION['class_resolver'])) {
            $cache = json_decode($_SESSION['class_resolver'], true);
        }
        $cache[$key] = $value;
        $_SESSION['class_resolver'] = json_encode($cache);
    }
}
?>