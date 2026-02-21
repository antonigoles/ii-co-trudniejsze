<?php

namespace App\CourseModifier\Modifiers;

class Resolve implements ICourseModifier
{
    public function __construct(
        private string $key,
        private string $starts_with,
        private string $resolves_to_key,
        private string $resolves_to_value,
    ) {}

    public function apply(array $course): ModifierStepResult
    {
        if (!isset($course[$this->key])) {
            return ModifierStepResult::Continue($course);
        }

        $value_to_test = $course[$this->key];

        if (str_starts_with($value_to_test, $this->starts_with)) {
            $course[$this->resolves_to_key] = $this->resolves_to_value;
        }

        return ModifierStepResult::Continue($course);
    }

    public static function from_array(array $data): Resolve
    {
        return new Resolve(
            key: $data["key"] ?? '',
            starts_with: $data["starts_with"] ?? '',
            resolves_to_key: $data["resolves_to"]['key'] ?? 'resolved_value',
            resolves_to_value: $data["resolves_to"]['value'] ?? '',
        );
    }
}

?>