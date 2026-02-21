<?php

namespace App\CourseModifier\Modifiers;

interface ICourseModifier
{
    public function apply(array $course): ModifierStepResult;

    public static function from_array(array $data);
}

?>