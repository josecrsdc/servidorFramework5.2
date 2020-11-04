<?php

    require __DIR__ . '/../vendor/autoload.php';
    $templatesFolder = __DIR__ . '/../app/views';
    $templates = League\Plates\Engine::create($templatesFolder);

    //librería para poder leer archivos.env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    
    include __DIR__ . ('/../routes/web.php');
