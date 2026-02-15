<?php

namespace App;

class Question 
{
    public function __construct(
        private string $option_a,
        private string $option_b,
    ) {}

    public function get_hash(): string
    {
        return hash('sha256', $this->option_a . $this->option_b);
    }

    public function get_option_a(): string
    {
        return $this->option_a;
    }

    public function get_option_b(): string
    {
        return $this->option_b;
    }

    public function to_array(): array 
    {
        return [
            'option_a' => $this->option_a,
            'option_b' => $this->option_b,
        ];
    }

    public static function from_array(array $data): Question 
    {
        return new Question(
            $data['option_a'], 
            $data['option_b'] 
        );
    }
}
?>