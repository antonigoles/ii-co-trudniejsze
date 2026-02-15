<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\OAuth;

    echo OAuth::fetch_user_id();
?>