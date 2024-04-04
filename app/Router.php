<?php
namespace App;

use App\Http\Request;
use App\Http\Response;
use App\Services\DataBaseClient;

class Router {
    private $postRoutes = [];
    private $getRoutes = [];
    private $deleteRoutes = [];

    public function add($uri, $controller, $action, $method, $needAuth = false) {
        if($method == 'POST') {
            $this->postRoutes[$uri] = [$controller , $action, $needAuth];
        }

        if($method == 'GET') {
            $this->getRoutes[$uri] = [$controller , $action, $needAuth];
        }

        if($method == 'DELETE') {
            $this->deleteRoutes[$uri] = [$controller , $action, $needAuth];
        }
    }

    public function run($uri, Request $request, DataBaseClient $dbClient) {

        if($request->method == 'POST') {
            $routes = $this->postRoutes;
        }
        else if($request->method == 'GET') {
            $routes = $this->getRoutes;
        }
        else if($request->method == 'DELETE') {
            $routes = $this->deleteRoutes;
        }
        
        if(array_key_exists($uri, $routes)) {
            
            $route = $routes[$uri];
            $needAuth = $route[2];

            if($needAuth) {
                if(!Auth::check()) {
                    Response::redirect('/login');
                }
            }

            
            $controllerClass = 'App\Controllers\\'.$route[0];
            $controllerInstance = new $controllerClass($dbClient);
            $controllerInstance->{$route[1]}($request);

        } else {
            echo 'Page not found!';
            die();
        }
    }


}