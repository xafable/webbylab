<?php 

namespace App\Http;

class Response
{
    public static function json($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);
        
        die();
    }

    public static function redirect($url)
    {
        
        $host = $_SERVER['HTTP_HOST'];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        header('Location: '. $protocol . $host . $url);

        die();
       
    }

    public static function View($view, $data = [])
    {
        
        require_once __DIR__ . '/../Views/' . $view . '.php';

    }
}