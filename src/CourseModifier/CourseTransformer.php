<?php

namespace App\CourseModifier;

use App\CourseModifier\Modifiers\ICourseModifier;
use App\CourseModifier\Modifiers\Ignore;
use App\CourseModifier\Modifiers\Resolve;
use App\Secrets;

class CourseTransformer
{
    public static function resolve_modifier(array $data): ICourseModifier|null
    {
        return match ($data['type'] ?? 'default') {
            'ignore' => Ignore::from_array($data),
            'resolve' => Resolve::from_array($data),
            default => throw new \Exception('Non existent modifier')
        };
    }

    public static function transform_from_config(array $course): array|null 
    {
        $course_modifiers = Secrets::get()->get_secret(Secrets::COURSE_MODIFIERS);
        $course_data = $course;

        foreach ($course_modifiers as $modifier_data) {
            $modifier = self::resolve_modifier($modifier_data);
            $result = $modifier->apply($course_data);
            if (!$result->can_continue()) {
                return null;
            }
            $course_data = $result->get_data();
        }

        return $course_data;
    }
}

?>