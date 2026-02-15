<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Secrets;
use App\OAuth;

$secrets = new Secrets();
$secrets->load_data();

// 1. we land onto this php script from a button click
if (!isset($_GET['oauth_token']) || !isset($_GET['oauth_verifier'])) {
    // Let's start new session
    session_start();

    // 2. Aquire request token like usos says

    $response = OAuth::send_oauth1_request(
        'GET', 
        'https://usosapps.uwr.edu.pl/services/oauth/request_token',
        [
            'oauth_callback' => $secrets->get_secret(Secrets::APP_URL).'/usos_oauth.php'
        ],
        $secrets->get_secret(Secrets::USOS_CONSUMER_KEY),
        $secrets->get_secret(Secrets::USOS_CONSUMER_SECRET)
    );

    // parse variables
    $params = [];
    parse_str($response, $params);

    if (!isset($params['oauth_token']) || !isset($params['oauth_token_secret'])) {
        echo 'Niepoprawna odpowiedź z USOSa. Spróbuj później';
        session_destroy();
        die();
    }

    $_SESSION['oauth_token'] = $params['oauth_token'];
    $_SESSION['oauth_token_secret'] = $params['oauth_token_secret'];

    // redirect to authorize page
    $authorize_url = "https://usosapps.uwr.edu.pl/services/oauth/authorize?$response";
    header("Location: $authorize_url");
    die();
} else {
    // 3. We came back from the OAuth page
    session_start();

    // 4. Verify saved tokens
    if (!isset($_SESSION['oauth_token']) || !isset($_SESSION['oauth_token_secret'])) {
        echo 'Wykryto potencjalny atak CSRF';
        session_destroy();
        die();
    }

    if ($_SESSION['oauth_token'] != $_GET['oauth_token']) {
        echo 'Wykryto potencjalny atak CSRF';
        session_destroy();
        die();
    }

    // ok now let's get the access token
    $response = OAuth::send_oauth1_request('GET', "https://usosapps.uwr.edu.pl/services/oauth/access_token",
    [
            "oauth_verifier" => $_GET['oauth_verifier']
        ],
        $secrets->get_secret(Secrets::USOS_CONSUMER_KEY),
        $secrets->get_secret(Secrets::USOS_CONSUMER_SECRET),
        $_SESSION['oauth_token'],
        $_SESSION['oauth_token_secret']
    );

    $params = [];
    parse_str($response, $params);

    if (!isset($params['oauth_token']) || !isset($params['oauth_token_secret'])) {
        echo 'Niepoprawna odpowiedź z USOSa. Spróbuj później';
        session_destroy();
        die();
    }

    $_SESSION['token_creation_timestamp'] = time();
    $_SESSION['oauth_token'] = $params['oauth_token'];
    $_SESSION['oauth_token_secret'] = $params['oauth_token_secret'];

    $base_app_url = $secrets->get_secret(Secrets::APP_URL);

    header("Location: $base_app_url");
    die();
}

?>