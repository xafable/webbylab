<?php

namespace App\Http;

class Request
{
    public $method;
    public $uri;
    public $wantsJson = false;
    private $data;

    public function __construct($method, $uri, $data)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->data = $data;

        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $this->wantsJson = true;
        }

    }

    public static function makeFromGlobals()
    {
        $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

        if(isset($uri_parts[0])) {
            $uri = $uri_parts[0];
        }
        else {
            $uri = '/';
        }

        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(empty($_POST)){
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            }
            else {
                $data = $_POST;
            }

            return new static($_SERVER['REQUEST_METHOD'], $uri, $data);
        }
        else if($_SERVER['REQUEST_METHOD'] == 'GET') {
            return new static($_SERVER['REQUEST_METHOD'], $uri, $_GET);
        }
        else if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            return new static($_SERVER['REQUEST_METHOD'], $uri, $data);
        }
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function has($name)
    {
        return isset($this->data[$name]);
    }
}