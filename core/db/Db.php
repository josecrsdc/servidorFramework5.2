<?php

namespace core\db;
use core\db\Connection;

    class Db {
        
        //Metodos

        private function execute($sql, $params) {
            $pdo = Connection::getInstance()::getPdo();
            $ps = $pdo->prepare($sql);
            $ps->execute($params);
            $inicioSentencia = explode(" ", $sql)[0];
            $tipoConsulta = ["INSERT", "UPDATE", "DELETE"];
            if (!in_array($inicioSentencia, $tipoConsulta)) {
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

        private function edit($table, $editValues, $pkName, $pkValue) {
            $fields = '';
            $params = array();
            foreach ($editValues as $key => $value) {
                if ($key !== '_method') {
                    $fields .= "$key = :$key,";
                    $param = ':' . $key;
                    $params[$param] = $value;
                }
            }
            $fields = \substr($fields, 0, -1);
            $where = "$pkName = :id";
            $params[":id"] = $pkValue;
            $sql = "UPDATE $table SET $fields WHERE $where";
            return $this->execute($sql, $params);
        }

        private function delete($table, $pkName, $pkValue) {
            $params = array();
            $where = "$pkName = :id";
            $params[":id"] = $pkValue;
            $sql = "DELETE FROM $table WHERE $where";
            return $this->execute($sql, $params);
        }
            

        
        
        public static function __callStatic($name, $arguments) {
            return (new static)->$name(...$arguments);
        }

    }
    