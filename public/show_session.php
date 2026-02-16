<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Session;

Session::start_session();

header('Content-Type: application/json; charset=utf-8');
echo json_encode($_SESSION);

?>