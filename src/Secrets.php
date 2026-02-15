<?php
namespace App;

class Secrets 
{
    private array $data;

    public const USOS_APP_NAME = 'name';
    public const USOS_CONSUMER_KEY = 'consumer_key';
    public const USOS_CONSUMER_SECRET = 'consumer_secret';
    public const APP_URL = 'app_url';

    public function load_data(): void
    {
        $file_content = file_get_contents(__DIR__ .'/../config.json');
        $this->data = json_decode(
            $file_content, 
            true, 
            512,
            JSON_THROW_ON_ERROR
        ) ?? [];
    }

    public function get_secret(string $secret): string|null
    {
        return $this->data[$secret] ?? null;
    }
}
?>