<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\ClassResolver;
use App\OAuth;
use App\Questions;
use App\Secrets;
use App\Session;

Session::start_session();

header('Content-Type: application/json; charset=utf-8');
// echo '{ "message": "heelo" }'

echo json_encode(OAuth::fetch_user_courses_transformed());

// echo json_encode(OAuth::fetch_user_courses());
?>