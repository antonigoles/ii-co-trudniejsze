<?php

namespace App;

use App\OAuth;

class Questions 
{
    public const CLASS_CATEGORIES = [
        "kurs", "projekt", "seminarium", "przedmiot"
    ];

    public const VALID_QUESTION_OPTIONS = ['a','b','none'];

    public static function answer_question(Question $question, string $option) 
    {
        // TODO: Connect to database and write data
    }

    public static function get_next_question(): Question|null
    {
        if (OAuth::should_reauthenticate()) {
            return null;
        }
        // 1. Choose random category
        $category = self::CLASS_CATEGORIES[rand(0, count(self::CLASS_CATEGORIES) - 1)];

        // 2. Read .json
        $file_content = file_get_contents(__DIR__ .'/../class_list.json');
        $data = json_decode(
            $file_content, 
            true, 
            512,
            JSON_THROW_ON_ERROR
        ) ?? [];
        
        // 2. Choose 2 random (non-repeating) classes
        $pool = $data[$category];
        $random_choice_a_index = rand(0, count($pool) - 1);
        $option_a = $pool[$random_choice_a_index];
        $pool[$random_choice_a_index] = $pool[count($pool)-1];
        array_pop($pool);
        $random_choice_b_index = rand(0, count($pool) - 1);
        $option_b = $pool[$random_choice_b_index];

        return new Question($option_a, $option_b);
    }
}

?>