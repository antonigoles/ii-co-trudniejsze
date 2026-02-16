<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
    use App\Questions;
    use App\Session;

    header('Content-Type: application/json; charset=utf-8');

    function error_out(string $message) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo "{ \"error\": \"$message\" }";
        die();
    }

    if (OAuth::should_reauthenticate()) {
        Session::kill_session();
        error_out("Sesja jest martwa");
    }

    try {
        if (!isset($_SESSION["current_question"])) {
            $question = Questions::get_next_question();
            $_SESSION["current_question"] = json_encode($question->to_array());
        }
    } catch (\Throwable $th) {
        Session::kill_session();
        error_out("Sesja jest martwa");
    }

    http_response_code(response_code: 200);
    echo $_SESSION["current_question"];
?>