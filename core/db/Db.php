<?php

namespace core\db;
use core\db\Connection;

    class Db {
        
        //Metodos

        private function execute($sql, $params) {
            $pdo = Connection::getInstance()::getPdo();
            $ps = $pdo->prepare($sql);
            $ps->execute($params);
            $esInsert = explode(" ", $sql)[0];
            if ($esInsert != "INSERT") {
                return $ps->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
            
        private function select($table, $fields = ['*'], $where = null, $params = null) {
            $sql = "SELECT ";
            foreach ($fields as $field) {
                $sql .= "$field, " ;
            }
            $sql = substr($sql, 0 ,-2);
            $sql .= " FROM $table";

            if ($where != null) {
                $sql .= " WHERE $where";
            }
            return Db::execute($sql, $params);
        }

        protected function insert($table, $insertValues) {
            $fields = '(';
            $values = '(';
            $params = array();
            foreach ($insertValues as $key => $value) {
                $fields .= $key . ',';
                $param = ':' . $key;
                $values .= $param . ',';
                $params[$param] = $value;
            }
            $fields = \substr($fields, 0, -1) . ')';
            $values = \substr($values, 0, -1) . ')';
            $sql = "INSERT INTO $table $fields VALUES $values";
            return Db::execute($sql, $params);
        }

        
        
        public static function __callStatic($name, $arguments) {
            return (new static)->$name(...$arguments);
        }

    }
    