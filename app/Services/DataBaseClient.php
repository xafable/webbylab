<?php

namespace App\Services;

use PDO;
use Exception;
use PDOException;

class DataBaseClient 
{
    private $client;
    private $table = '';

    private $conditions = [];
    private $orConditions = [];
    private $columns = [];
    private $joins = [];
    private $orderBy = '';
    private $orderDir = ''; 

    private $query = '';

    const EXEC_TYPE_SELECT = 'SELECT';
    const EXEC_TYPE_DELETE = 'DELETE';



    public function __construct($host,$db,$username,$password)
    {
        if(!isset($this->client)) {
            $this->connect($host,$db,$username,$password);
        }

        $this->reset();

        return $this;
    }

    public function connect($host,$db,$username,$password)
    {
        try {
            $this->client = new PDO("mysql:host=$host;dbname=$db", $username, $password);
            $this->client->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //$this->instance = $this;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function where($column, $operator, $value)
    {

        $this->conditions[] = [$column, $operator, $value];
       
        return $this;
       
    }


    public function orWhere($column, $operator, $value)
    {

        $this->orConditions[] = [$column, $operator, $value];
    }

    public function join($table, $column1, $column2)
    {
        $this->joins[] = [$table, $column1, $column2];
    }


    public function table(string $table)
    {
        $this->table = $table;

        return $this;
    }

    
    public function select(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function insert(array $data)
    {

        if(empty($data)) {
            throw new Exception("Empty data for insert");
        }

        $columns = implode(',',array_keys($data));
        $data = array_values($data);

        $marks = implode(',',array_map(function() { return '?'; }, $data));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($marks)";

     
        $statement= $this->client->prepare($sql);
        $statement->execute($data);

        $lastInsertedId = $this->client->lastInsertId();

        return $lastInsertedId;
    }


    public function get($model = false)
    {
     
        

        
       
        $statement = $this->prepareQuery(self::EXEC_TYPE_SELECT);
        $statement->execute();

        $this->reset();

        if(!$model) {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $rows  = $statement->fetchAll(PDO::FETCH_ASSOC);
            $models = [];
            foreach($rows as $row) {
                $models[] = new $model($row);
            }

            return $models;
        }
    }


    public function delete()
    {
        
        $statement = $this->prepareQuery(self::EXEC_TYPE_DELETE);
        $statement->execute();
        $affectedRows = $statement->rowCount();

        return $affectedRows;

    }

    public function exists($column, $value)
    {

        $sql = "SELECT id FROM $this->table WHERE $column = :value";

        $stmt = $this->client->prepare($sql);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        $id  = $stmt->fetchColumn();

        if ($id) {
             return $id ;
        } else {
             return false;
        }
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->orderBy = $column;
        $this->orderDir = $order;

        return $this;
    }
        


    public function prepareQuery($type = 'SELECT')
    {
        $columns = '*';
        if(count($this->columns) > 0) {
            $columns = implode(',', $this->columns);
        }
   
        $condition = '1 = 1';
        if(count($this->conditions) > 0) {
        foreach($this->conditions as $key => $item) {
            $valName = '_val'.$key;
            $operator = $item[1];
            if($operator == 'IN') {
                $condition .= " AND FIND_IN_SET({$item[0]}, :__{$valName}) ";
            }
            else {
                $condition .= " AND {$item[0]} {$item[1]} :__{$valName} ";
            }
            
         }
        }


        if(count($this->orConditions) > 0) {
            foreach($this->orConditions as $key => $item) {
                $valName = '_valr'.$key;
                $operator = $item[1];
                if($operator == 'IN') {
                    $condition .= " OR FIND_IN_SET({$item[0]}, :__{$valName}) ";
                }
                else {
                    $condition .= " OR {$item[0]} {$item[1]} :__{$valName} ";
                }
                
             }
        }

        $joins = '';
        if(count($this->joins) > 0) {
            foreach($this->joins as $key => $item) {
                $joins .= " JOIN {$item[0]} ON {$item[1]} = {$item[2]} ";
            }
        }

        $order = '';
        if($this->orderBy) {
            $order = " ORDER BY $this->orderBy  COLLATE  utf8mb4_unicode_ci $this->orderDir";
        }


        if($type == self::EXEC_TYPE_SELECT) {
            $this->query = "SELECT $columns FROM $this->table $joins WHERE $condition $order";
        }

        else if($type == self::EXEC_TYPE_DELETE) {
            $this->query = "DELETE FROM $this->table WHERE $condition $order";
        }

       
        $stmt = $this->client->prepare($this->query);

        //var_dump($this->query);
        

        if(count($this->conditions) > 0){
            foreach($this->conditions as $key => $item) {
                $valName = '_val'.$key;
    
                $stmt->bindParam(":__".$valName, $item[2], PDO::PARAM_STR);
            }
        }

        if(count($this->orConditions) > 0){
            foreach($this->orConditions as $key => $item) {
                $valName = '_valr'.$key;
                $stmt->bindParam(":__".$valName, $item[2], PDO::PARAM_STR);
            }
        }
       
        return $stmt;

    }

    private function reset()
    {
        $this->table = '';
        $this->conditions = [];
        $this->columns = [];
        $this->joins = [];
        $this->query = '';
        $this->orderBy = '';
        $this->orderDir = '';
    }
}