<?php
    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        $basedir = parse_url($_ENV['APP_URL'], PHP_URL_PATH);;
        $r->addRoute('GET', $basedir . '/', 'main@index');
        $r->addRoute('GET', $basedir . '/peliculas', 'Pelicula@getAll');
        $r->addRoute('GET', $basedir . '/peliculas/{id:\d+}', 'Pelicula@getById');
        $r->addRoute('GET', $basedir . '/peliculas/{id:\d+}/criticas', 'Pelicula@criticas');
        $r->addRoute('POST', $basedir . '/peliculas/{id:\d+}/criticas', 'Critica@insert');
        $r->addRoute('GET', $basedir . '/peliculas/{id:\d+}/criticas/insertar', 'Critica@insertForm');
        $r->addRoute('GET', $basedir . '/directores/{id:\d+}', 'Director@getById');
        $r->addRoute('GET', $basedir . '/actores/{id:\d+}', 'Actor@getById');
        $r->addRoute('GET', $basedir . '/criticas', 'Critica@getAll');
        $r->addRoute('GET', $basedir . '/criticas/{id:\d+}', 'Critica@getById');
        $r->addRoute('GET', $basedir . '/peliculas/{id_pelicula:\d+}/criticas/editar/{id_critica:\d+}', 'Critica@editForm');
        $r->addRoute('PUT', $basedir . '/peliculas/{id_pelicula:\d+}/criticas/{id_critica:\d+}', 'Critica@edit');
        $r->addRoute('DELETE', $basedir . '/peliculas/{id_pelicula:\d+}/criticas/{id_critica:\d+}', 'Critica@delete');
    });

    // Fetch method and URI from somewhere
    //Ver si existe el campo _method para sobreescribir los métodos put y delete ya que los formularios sólo permiten POST y GET
    $metodosPermitidos = ['GET', 'POST', 'PUT', 'DELETE'];
    $httpMethod = strtoupper($_POST['_method']?? $_SERVER['REQUEST_METHOD']);
    if(!in_array($httpMethod, $metodosPermitidos)) {
    $httpMethod = 'GET';
    }

    $uri = $_SERVER['REQUEST_URI'];

    // Strip query string (?foo=bar) and decode URI
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }

    $uri = rawurldecode($uri);
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $controllerName = '\\app\\controllers\\Main';
            $action = 'error';
            $controller = new $controllerName($templates);
            $controller->$action();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $partes = explode('@', $handler);
            $controllerName = '\\app\\controllers\\' . ucfirst($partes[0]);
            $action = $partes[1];
            $controller = new $controllerName($templates);
            $vars = $routeInfo[2];
            $controller->$action($vars);
            break;
    }
    