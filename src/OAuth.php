<?php

namespace App;

class OAuth 
{
    public static function should_reauthenticate(): bool 
    {
        session_start();

        if (!isset($_SESSION['token_creation_timestamp'])) {
            return true;
        }

        $timestamp = $_SESSION['token_creation_timestamp'];
            
        if (!is_numeric($timestamp)) {
            return true;
        }

        if (time() - intval($timestamp) >= 2 * 60 * 60) {
            // it's been more than 2 hours
            return true;
        }

        return false;
    }

    public static function fetch_user_data(): array|null
    {
        $secrets = new Secrets();
        $secrets->load_data();

        if (self::should_reauthenticate()) return null;

        $response = self::send_oauth1_request(
            'GET',
            'https://usosapps.uwr.edu.pl/services/users/user',
            [
                'fields' => 'id|first_name|last_name'
            ],
            $secrets->get_secret(Secrets::USOS_CONSUMER_KEY),
        $secrets->get_secret(Secrets::USOS_CONSUMER_SECRET),
        $_SESSION['oauth_token'],
        $_SESSION['oauth_token_secret']
        );

        return json_decode($response, true) ?? null;
    }

    public static function fetch_user_id(): string|null
    {
        $data = self::fetch_user_data();
        return $data['id'] ?? null;
    }

    public static function send_oauth1_request($method, $url, $params, $consumerKey, $consumerSecret, $token = '', $tokenSecret = '') {
        // 1. Podstawowe parametry OAuth
        $oauth = [
            'oauth_consumer_key'     => $consumerKey,
            'oauth_nonce'            => md5(mt_rand() . microtime()),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0'
        ];

        if ($token) {
            $oauth['oauth_token'] = $token;
        }

        // 2. Łączymy parametry OAuth z Twoimi parametrami (np. $_POST/$_GET)
        $allParams = array_merge($oauth, $params);

        // 3. Sortowanie i kodowanie (Kluczowe dla OAuth 1.0!)
        ksort($allParams); // Sortowanie alfabetyczne kluczy
        
        $encodedParts = [];
        foreach ($allParams as $key => $value) {
            // RFC 3986: rawurlencode jest tutaj obowiązkowe
            $encodedParts[] = rawurlencode($key) . '=' . rawurlencode($value);
        }
        $parameterString = implode('&', $encodedParts);

        // 4. Budowanie Base String (To jest to, co podpisujemy)
        $baseString = strtoupper($method) . '&'
                    . rawurlencode($url) . '&'
                    . rawurlencode($parameterString);

        // 5. Tworzenie klucza podpisu
        $signingKey = rawurlencode($consumerSecret) . '&' . rawurlencode($tokenSecret);

        // 6. Generowanie podpisu
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));

        // 7. Budowanie nagłówka Authorization
        // Ważne: Do nagłówka trafiają TYLKO parametry oauth_*, a nie Twoje dane biznesowe
        $authHeaderParts = [];
        foreach ($oauth as $key => $value) {
            $authHeaderParts[] = $key . '="' . rawurlencode($value) . '"';
        }
        $header = 'Authorization: OAuth ' . implode(', ', $authHeaderParts);

        // 8. Wysłanie requestu przez cURL
        $ch = curl_init();
        
        // Konfiguracja URL i parametrów
        if (strtoupper($method) === 'GET') {
            // Dla GET parametry idą w URL
            $requestUrl = $url . ($params ? '?' . http_build_query($params) : '');
            curl_setopt($ch, CURLOPT_URL, $requestUrl);
        } else {
            // Dla POST parametry idą w body
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [$header, 'Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_SSL_VERIFYPEER => false // Na produkcji ustaw na true!
        ]);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('Błąd cURL: ' . curl_error($ch));
        }
            
        return $response;
    }
}

?>