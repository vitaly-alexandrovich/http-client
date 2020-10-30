<?php

require __DIR__ . '/../vendor/autoload.php';

use HttpClient\Client;

$client = new Client();

// Инициализация

$testCase = new \HttpClient\testcase\TestCase();
Client::setTestCase($testCase);
Client::getTestCase(); // можно настроить в дальнейшем
// Client::disableTestCase();

// -------------


// Подмена исключений

$testCase->setException(new Exception('Debug exception'));
try {
    $client->get('https://yandex.ru');
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
$testCase->unsetException();

// -------------------


// Подмена ответа

$testCase->setResponse(new \HttpClient\Response('Debug content'));
echo $client->get('https://yandex.ru')->getBody() . PHP_EOL;
$testCase->unsetResponse();

// --------------


// Подмена настроек

$testCase->setOptions([
    CURLOPT_URL => 'https://www.google.com/'
]);
print_r($client->get('https://yandex.ru')->getHeaders()); // google

// ----------------


// Отключение

Client::disableTestCase();
print_r($client->get('https://yandex.ru')->getHeaders()); // yandex

// ----------
