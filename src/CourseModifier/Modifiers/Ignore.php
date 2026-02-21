<?php

namespace App\CourseModifier\Modifiers;

class Ignore implements ICourseModifier
{
    public function __construct(
        private string $key,
        private array $one_of,
        private string $not_starts_with
    ) {}

    public function apply(array $course): ModifierStepResult
    {
        if (!isset($course[$this->key])) {
            return ModifierStepResult::Break();
        }

        $value_to_test = $course[$this->key];
        
        if (in_array($value_to_test, $this->one_of)) {
            return ModifierStepResult::Break();
        }

        if (!str_starts_with($value_to_test, $this->not_starts_with)) {
            return ModifierStepResult::Break();
        }

        return ModifierStepResult::Continue($course);
    }

    public static function from_array(array $data): Ignore
    {
        return new Ignore(
            key: $data['key'] ?? '',
            one_of: $data['one_of'] ?? [],
            not_starts_with: $data['not_starts_with'] ?? ''
        );
    }
}

?>