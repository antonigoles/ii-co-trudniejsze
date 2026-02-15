<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\OAuth;
    use App\Question;
    use App\Questions;

    if (!isset($_GET['option'])) {
        echo 'Niepoprawna opcja';
        http_response_code(403);
        die();
    }

    if (OAuth::should_reauthenticate()) {
        echo 'Sesja jest martwa';
        http_response_code(403);
        die();
    }

    $option = $_GET['option'];

    if (!in_array($option, Questions::VALID_QUESTION_OPTIONS)) {
        echo 'Niepoprawna opcja';
        http_response_code(403);
        die();
    }

    // pull question
    $question = $_SESSION['current_question'] ?? null;
    if (!$question) {
        echo 'Nie pamiętam żebyś miał odpowiadać na jakieś pytanie';
        http_response_code(403);
        die();
    }

    $question = json_decode($question, true);
    if (!$question) {
        echo 'Nie pamiętam żebyś miał odpowiadać na jakieś pytanie';
        http_response_code(403);
        die();
    }

    $question = Question::from_array($question);
    if (!$question) {
        echo 'Nie pamiętam żebyś miał odpowiadać na jakieś pytanie';
        http_response_code(403);
        die();
    }

    Questions::answer_question($question, $option);

    unset($_SESSION["current_question"]);
    http_response_code(200);
    echo "OK";
?>