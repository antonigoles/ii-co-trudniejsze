<?php

namespace App;

use App\OAuth;
use App\ClassResolver;

class Questions 
{
    use CachableTrait;

    public const QUESTION_COUNT = 4403;

    public const VALID_QUESTION_OPTIONS = ['a','b','none'];

    public static function answer_question(Question $question, string $option) 
    {
        if (!in_array($option, self::VALID_QUESTION_OPTIONS)) {
            throw new \Exception("Wrong option");
        }

        $user_id = OAuth::fetch_user_id();

        if ($user_id == null) {
            throw new \Exception('Auth error');
        }

        $connection = DatabaseConnection::get();

        $connection->query(
            "INSERT INTO answers 
            VALUES (:user_id, :question, :answer)",
            
            [
                "user_id" => $user_id,
                "question" => $question->get_id(),
                "answer" => $option
            ]
        );

        $cache_hit = self::try_read_from_cache("answered_count");
        if ($cache_hit) {
            self::write_cache("answered_count", intval($cache_hit) + 1);
        }
    }


    // TODO: Rewrite this
    public static function fetch_valid_question_id_list(): array
    {
        if (OAuth::should_reauthenticate()) {
            throw new \Exception('Auth error');
        }

        $cache_hit = self::try_read_from_cache('valid_question_list');
        if ($cache_hit) {
            return $cache_hit;
        }

        $classes = array_values(ClassResolver::match_classes_from_usos_to_local());
        $classes_query_string = implode(',', array_map(static fn ($class) => "'$class'", $classes));

        // match class to id
        $connection = DatabaseConnection::get();
        $class_ids = $connection->query_field(
            "SELECT * FROM classes WHERE name IN ($classes_query_string)", 
            [],
            'id'
        );    

        $class_ids_string = implode(',', $class_ids);

        $resolved_classes = $connection->query_field(
            "SELECT * FROM questions WHERE option_a IN ($class_ids_string) AND option_b IN ($class_ids_string)", 
            [],
            'id'
        );

        self::write_cache('valid_question_list', $resolved_classes);

        return $resolved_classes;
    }

    public static function get_next_question(): Question|null
    {
        if (OAuth::should_reauthenticate()) {
            return null;
        }

        $connection = DatabaseConnection::get();

        $valid_question_list = self::fetch_valid_question_id_list();

        if (empty($valid_question_list)) {
            return null;
        }

        $random_question_id = $valid_question_list[rand(0, count($valid_question_list) - 1)];

        $question = $connection->query(
            "SELECT
                        questions.id, 
                        opt_a.name as option_a,
                        opt_b.name as option_b
                    FROM questions 
                    JOIN classes opt_a ON opt_a.id = option_a
                    JOIN classes opt_b ON opt_b.id = option_b
                    WHERE questions.id = :question_id;", 
            [
                "question_id" => $random_question_id
            ]
        );

        $question = $question[0];
        
        return new Question($question['id'], $question['option_a'], $question['option_b']);
    }

    /**
     * This method is session safe which means we don't care about 
     * correct results that much so we can skip handling dead session
     * @return int
     */
    public static function get_answered_question_count(): int
    {
        if (OAuth::should_reauthenticate()) {
            return 0;
        }

        $cache_hit = self::try_read_from_cache("answered_count");

        if ($cache_hit) {
            return intval($cache_hit);
        }

        $connection = DatabaseConnection::get();

        try {
            $user_id = OAuth::fetch_user_id();
        } catch (\Throwable $th) {
            return 0;
        }

        $result = $connection->query_field(
            "SELECT count(*) FROM answers WHERE owner_id = :owner_id;",
            [
                "owner_id" => $user_id
            ],
            'count'
        );

        self::write_cache("answered_count", intval($result[0]));
        return $result[0];
    }
}

?>