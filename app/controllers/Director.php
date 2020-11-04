<?php

    namespace app\controllers;
    use core\Controller;
    use app\models\Director as modelDirector;

    class Director extends Controller{
        
        function getById($vars) {
            $director = modelDirector::find($vars['id']);
            echo $this->templates->render('directores_ficha', ['director' => $director]);
            }
    }