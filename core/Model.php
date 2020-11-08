<?php
    namespace core;
    use core\db\Db;
    
    class Model {

        protected $table;
        public $primary_key = 'id';

        protected function all() {
            $result = Db::select($this->table);
            
            return $result;
        }

        protected function find($pk) {
            $fields = ['*'];
            $where = $this->primary_key . " = :id";
            $params = [
                ":id" => $pk
            ];
            $result = Db::select($this->table, $fields, $where, $params);
            
            return $result[0];
        }

        protected function belongsToMany($t2, $tj, $pk_tJ1, $pk_tJ2, $pk, $pk_t2 = 'id') {
            $sql = 'SELECT a.* FROM ' . $t2 . ' a ' . 
                'JOIN ' . $tj . ' pa ON a.' . $pk_t2 . ' = pa.' . $pk_tJ2 . ' ' .
                'JOIN ' . $this->table . ' p ON p.' . $pk_t2 . '=pa.' . $pk_tJ1 . ' AND p.' . $this->primary_key . ' = :id';
            
            $params = [
                ":id" => $pk
            ];
            return Db::execute($sql, $params);
        }

        protected function hasMany($t2, $fk_t2, $pk) {
            $sql = "SELECT t2.* FROM $t2 t2
                    JOIN $this->table t1 ON t1.$this->primary_key = t2.$fk_t2
                    AND t1.$this->primary_key = :id";
                    
            $params = [
                ":id" => $pk
            ];
            return Db::execute($sql, $params);
        }

        protected function insert() {
            $result = Db::insert($this->table, $_POST);
            return $result;
        }


        
        public static function __callStatic($name, $arguments) {
            return (new static)->$name(...$arguments);
        }
    }