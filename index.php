<?php

require __DIR__ . '/Bootstrap/app.php';
$app->get('/', function ($request, $response, $args) {
    return $response->withStatus(200)->write('Hello World!');
});
$app->run();

?>