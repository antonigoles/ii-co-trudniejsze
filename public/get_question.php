<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
    use App\Questions;

    if (OAuth::should_reauthenticate()) {
        echo 'Sesja jest martwa';
        http_response_code(403);
        die();
    }

    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION["current_question"])) {
        $question = Questions::get_next_question();
        $_SESSION["current_question"] = json_encode($question->to_array());
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $_SESSION["current_question"];
?>