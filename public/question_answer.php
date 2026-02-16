<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
    use App\Question;
    use App\Questions;
    use App\Session;

    function error_out(string $message) {
        Session::kill_session();
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo "{ \"error\": \"$message\" }";
        die();
    }

    if (!isset($_GET['option'])) {
        error_out("Sesja jest martwa");
    }

    if (OAuth::should_reauthenticate()) {
        error_out("Sesja jest martwa");
    }

    $option = $_GET['option'];

    if (!in_array($option, Questions::VALID_QUESTION_OPTIONS)) {
        error_out("Niepoprawna opcja");
    }

    // pull question
    $question = $_SESSION['current_question'] ?? null;
    if (!$question) {
        error_out("Nie pamiętam żebyś miał odpowiadać na jakieś pytanie");
    }

    $question = json_decode($question, true);
    if (!$question) {
        error_out("Nie pamiętam żebyś miał odpowiadać na jakieś pytanie");
    }

    $question = Question::from_array($question);
    if (!$question) {
        error_out("Nie pamiętam żebyś miał odpowiadać na jakieś pytanie");
    }

    try {
        Questions::answer_question($question, $option);
    } catch (\Throwable $th) {
        error_out("auth error");
    }

    unset($_SESSION["current_question"]);
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo '{ "success": "OK" }';
?>