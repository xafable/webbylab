<?php

namespace App;

use App\Router;
use App\Http\Request;
use App\Http\Response;
use App\Services\DataBaseClient;

class Kernel
{
    
    public function handle()
    {
        session_start();

       
    
        $request = Request::makeFromGlobals();
        $router = new Router();

        $dbHost = Utils::env('DB_HOST', 'localhost');
        $dbUser = Utils::env('DB_USER', 'root');
        $dbName = Utils::env('DB_NAME', 'webbylab');
        $dbPass = Utils::env('DB_PASSWORD', '');

        $dbClient = new DataBaseClient($dbHost,  $dbName, $dbUser, $dbPass);

        $router->add('/','MoviesController','index','GET',true);

        $router->add('/login','AuthController','login','POST');
        $router->add('/logout','AuthController','logout','POST');
        $router->add('/login','AuthController','index','GET');

        $router->add('/register','AuthController','register','POST');
        $router->add('/register','AuthController','showRegister','GET');

        
        $router->add('/movie','MoviesController','show','GET',true);
        $router->add('/movies','MoviesController','index','GET',true);
        $router->add('/movies','MoviesController','create','POST',true);
        $router->add('/movies','MoviesController','delete','DELETE',true);

        $router->add('/upload','UploadController','handle','POST',true);

     

        $router->run($request->uri, $request, $dbClient);

     

    }
}