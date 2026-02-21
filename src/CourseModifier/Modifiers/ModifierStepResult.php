<?php

namespace App\CourseModifier\Modifiers;

class ModifierStepResult
{
    public function __construct(
        private bool $should_continue,
        private array $data = []
    ) {}


    public function can_continue(): bool
    {
        return $this->should_continue;
    }

    public function get_data(): array
    {
        return $this->data;
    }

    public static function Break(): ModifierStepResult {
        return new self(should_continue: false);
    }

    public static function Continue(array $with_data): ModifierStepResult {
        return new self(true, $with_data);
    }
}

?>