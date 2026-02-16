<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\OAuth;
use App\Secrets;
use App\Session;

$secrets = new Secrets();
$secrets->load_data();

if (!OAuth::should_reauthenticate()) {
    // kill the token
    
    OAuth::send_oauth1_request(
        'GET',
        'https://usosapps.uwr.edu.pl/services/oauth/revoke_token',
        [],
        $secrets->get_secret(Secrets::USOS_CONSUMER_KEY),
        $secrets->get_secret(Secrets::USOS_CONSUMER_SECRET),
        $_SESSION['oauth_token']
    );
}

Session::kill_session();

$base_app_url = $secrets->get_secret(Secrets::APP_URL);
header("Location: $base_app_url");
die();
?>