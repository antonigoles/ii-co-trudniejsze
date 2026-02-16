<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\ClassResolver;
use App\OAuth;
use App\Questions;
use App\Session;

Session::start_session();

header('Content-Type: application/json; charset=utf-8');
// echo '{ "message": "heelo" }'
echo json_encode(Questions::fetch_valid_question_id_list());
?>