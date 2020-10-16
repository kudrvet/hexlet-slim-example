<?php
/**
 * Реализуйте Маршрут /companies/{id}, по которому отдается json представление компании. Компания извлекается из списка $companies. Каждая компания представлена ассоциативным массивом у которого есть текстовый (то есть тип данных - строка) ключ id:
 *
 * Если компания с таким идентификатором не существует, то сайт должен вернуть ошибку 404 (страница с HTTP кодом 404) и текстом Page not found.
 *
 * Гипотетический пример показывающий структуру
 * $companies = [
 * ['id' => 4, .* другие свойства ],
 * ['id' => 2, /* другие свойства ],
 * ['id' => 8, /* другие свойства ]
 * ];
 *
 *
 * Подсказки
 * Для поиска нужной компании в списке компаний, воспользуйтесь методом firstWhere(), библиотеки Collection.
 * Статус ответа выставляется методом $response->withStatus($status). */

use Slim\Factory\AppFactory;

require __DIR__ . '/../../../vendor/autoload.php';
$companies = App\Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response, $args) {
    return $response->write('open something like (you can change id): /companies/5');
});

// BEGIN (write your solution here)


$app->get('/companies/{id}', function ($request, $response, $args) use ($companies) {
    $companiesCollection = collect($companies);
    $id = $args['id'];
    $company = $companiesCollection->firstWhere('id',$id);
    if(is_null($company)) {
        return $response->withStatus(404)->write("Page not found");
    }
    return $response->write(json_encode($company));

});
// END

$app->run();