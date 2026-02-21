# Co trudniejsze

Mały projekt zrobiony aby zebrać dane na temat tego co studenci Instytutu Informatyki WMI UWR myślą o trudności przedmiotów

Requirements na czystym serwerze:

```sh
php-curl # sudo apt install php-curl
php >= 8.3 # sudo apt install php8.3
```

Aby odpalić szybko, na czysto, przez kontener

```sh
docker compose up -d --build
```


.config.json format:

```json
{
    "name": <name of the USOS app>,
    "consumer_key": <USOS consumer key>,
    "consumer_secret": <USOS consumer secret key>,
    "app_url": <App url (default port is 8080)>,
    "allowed_course_fac_id": <A list of allowed course faculities (f.e. "2811,2812")>,
    "course_fac_id_map": {
        <faculity id>: <readable name>,
    }
}
```