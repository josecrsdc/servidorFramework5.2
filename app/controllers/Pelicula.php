<?php

    namespace app\controllers;
    use core\Controller;
    use app\models\Pelicula as modelPelicula;

    class Pelicula extends Controller{

        function getAll() {
            $peliculas = modelPelicula::all();
            echo $this->templates->render('peliculas_listado', ['peliculas' =>
            $peliculas]);
        }

        function getById($vars) {
            $pelicula = modelPelicula::find($vars['id']);
            $directores = modelPelicula::getDirectores($vars['id']);
            $actores = modelPelicula::getActores($vars['id']);
            echo $this->templates->render('peliculas_ficha', ['pelicula' => $pelicula, 'directores' => $directores,'actores' => $actores]);
            }
    }