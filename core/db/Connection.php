<?php

namespace core\db;
use PDO;

    class Connection {
        
        //Propiedades
        static private $instance;
        private $pdo;


        //Constructor
        function __construct() {
            try {
                $this->pdo = new \PDO($_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
                $this->pdo->exec("set names utf8");
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (\PDOException $e) {
                    echo "Error al conectar con la bbdd";
                }
        }

        //Metodos
        private function getPdo() {
            return $this->pdo;
        }

        private function getInstance() {
            if(self::$instance == null) {
                self::$instance = new Connection();
            }
                return self::$instance;
        }

        public static function __callStatic($name, $arguments) {
            return (new static)->$name(...$arguments);
        }
            
    
        
            
    }