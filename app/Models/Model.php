<?php 

namespace App\Models;

use App\Utils;
use App\Services\DataBaseClient;

class Model
{

    private static $dbClient;
    protected static $table;
    protected $attributes = [];


    public function __construct()
    {

    }

    public static function query()
    {
        if(!self::$dbClient) {
            self::_initDbClient();
        }

        self::$dbClient->table(static::$table);

        return new static();
    }

    public function dbClient()
    {
        return self::$dbClient;
    }

    public function where($column, $operator, $value)
    {

        self::$dbClient->where($column, $operator, $value);
       
        return new static();
       
    }


    public function orWhere($column, $operator, $value)
    {
        self::$dbClient->orWhere($column, $operator, $value);

        return new static();
    }

    
    public function select(array $columns)
    {

        self::$dbClient->select($columns);

        return new static();
    }

    public function join(string $table, string $column1, string $column2)
    {

        self::$dbClient->join($table, $column1, $column2);

        return new static();
    }

    public function orderBy(string $column, string $order)
    {

        self::$dbClient->orderBy($column, $order);

        return new static();
    }


    public function get()
    {

        return self::$dbClient->get(static::class);
    }

    public function create($data)
    {
        $id =  self::$dbClient->insert($data);

        $model = self::query()->where('id', '=', $id)->get()[0];

        return $model;
    }

    public function delete()
    {
        return self::$dbClient->delete();
    }


    private static function _initDbClient()
    {
        
        $dbHost = Utils::env('DB_HOST', 'localhost');
        $dbUser = Utils::env('DB_USER', 'root');
        $dbName = Utils::env('DB_NAME', 'webbylab');
        $dbPass = Utils::env('DB_PASSWORD', '');

        self::$dbClient = new DataBaseClient($dbHost,  $dbName, $dbUser, $dbPass);
    }

    public function __get($name)
    {
        return htmlspecialchars($this->attributes[$name]);
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function toArray()
    {
        return array_map(function($value) {
            if(!is_array($value)) {
                return htmlspecialchars($value);
            }
            else {
                return $value;
            }
        }, $this->attributes);
    }

  

}