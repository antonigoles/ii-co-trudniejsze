<?php

namespace App;

use App\OAuth;

class Questions 
{
    public const QUESTION_COUNT = 985;

    public const CLASS_CATEGORIES = [
        "kurs", "projekt", "seminarium", "przedmiot"
    ];

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

    public static function get_next_question(): Question|null
    {
        if (OAuth::should_reauthenticate()) {
            return null;
        }

        $connection = DatabaseConnection::get();

        $random_question_id = rand(1, self::QUESTION_COUNT + 1);

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

    public static function try_read_from_cache(string $key): mixed 
    {
        Session::start_session();
        if (!isset($_SESSION['questions_cache'])) return null;
        $cache = json_decode($_SESSION['questions_cache'], true);
        return $cache[$key] ?? null;
    }

    public static function write_cache(string $key, mixed $value): void 
    {
        Session::start_session();
        $cache = [];
        if (isset($_SESSION['questions_cache'])) {
            $cache = json_decode($_SESSION['questions_cache'], true);
        }
        $cache[$key] = $value;
        $_SESSION['questions_cache'] = json_encode($cache);
    }
}

?>