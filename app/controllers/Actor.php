<?php

    namespace app\controllers;
    use core\Controller;
    use core\db\Db;
    use app\models\Actor as modelActor;
    

    class Actor extends Controller{

        function getById($vars) {
            $actor = modelActor::find($vars['id']);
            echo $this->templates->render('actores_ficha', ['actor' => $actor]);
            }
    }