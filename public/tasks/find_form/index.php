<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../../../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория, в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../../../templates');
});
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$users = ['mike', 'mishel', 'adel', 'keks', 'kamila'];

$app->get('/users', function ($request, $response) use ($users) {

    $entered = htmlspecialchars($request->getQueryParam('entered'));

    if(empty($entered)) {
        $searchResult = [];
    } else {
        $searchResult = array_filter($users, function ($user) use ($entered) {
            return strpos($user, $entered) === 0;
        });
    }
    $params = ['users' => $users,'entered' => $entered, 'searchResult' => $searchResult];
    // Указанный путь считается относительно базовой директории для шаблонов, заданной на этапе конфигурации
    // $this доступен внутри анонимной функции благодаря https://php.net/manual/ru/closure.bindto.php
    // $this в Slim это контейнер зависимостей
    return $this->get('renderer')->render($response, 'find_form/users/index.phtml',$params);
});

$app->run();