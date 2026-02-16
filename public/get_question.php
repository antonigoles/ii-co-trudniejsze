<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
    use App\Questions;
    use App\Session;

    header('Content-Type: application/json; charset=utf-8');

    if (OAuth::should_reauthenticate()) {
        echo '{ "error": "Sesja jest martwa" }';
        http_response_code(403);
        die();
    }

    try {
        if (!isset($_SESSION["current_question"])) {
            $question = Questions::get_next_question();
            $_SESSION["current_question"] = json_encode($question->to_array());
        }
    } catch (\Throwable $th) {
        Session::kill_session();
        echo '{ "error": "Sesja jest martwa" }';
        http_response_code(403);
        die();
    }

    http_response_code(response_code: 200);
    echo $_SESSION["current_question"];
?>