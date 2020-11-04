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

        public function belongsToMany($t2, $tj, $pk_tJ1, $pk_tJ2, $pk, $pk_t2 = 'id') {
            $sql = 'SELECT a.* FROM ' . $t2 . ' a ' . 
                'JOIN ' . $tj . ' pa ON a.' . $pk_t2 . ' = pa.' . $pk_tJ2 . ' ' .
                'JOIN ' . $this->table . ' p ON p.' . $pk_t2 . '=pa.' . $pk_tJ1 . ' AND p.' . $this->primary_key . ' = :id';
            
            $params = [
                ":id" => $pk
            ];
            return Db::execute($sql, $params);
        }

        
        public static function __callStatic($name, $arguments) {
            return (new static)->$name(...$arguments);
        }
    }