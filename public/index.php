<?php

  namespace AUScraper;

  //Autoload Composer Dependencies
  require_once '../vendor/autoload.php';

  //Imports
  require_once '../controllers/RouteController.php';

  //Namespaces
  use Phroute\Phroute\RouteCollector;
  use Phroute\Phroute\Dispatcher;

  //Request Path
  $path = isset($_GET['path']) ? $_GET['path'] : '/';

  //Router Init
  $router = new RouteCollector();

  $router->get('/', function() {
    readfile('../views/index.html');
  });

  $router->controller('/api', 'AUScraper\\Routes');

  try {
    $dispatcher = new Dispatcher($router->getData());
    echo $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $path);
  } catch(\Exception $e) {
    http_response_code(404);
    readfile('./404.html');
  }
