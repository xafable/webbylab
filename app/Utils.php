<?php 

namespace App;

class Utils
{
    public static function env( $env, $default = null )
    {
        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            list($key, $value) = explode('=', $line, 2);
            if ($key === $env) {
                return $value;
            }
        }
        
        return $default;
    }

    public static function setFlashMessage($message, $type = 'danger') {
        $_SESSION['flash_message'] = [
            'message' => $message,
            'type' => $type
        ];
        
    }
    public static function displayFlashMessage() {
     
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message']['message'];
            $type = $_SESSION['flash_message']['type'];
    
        
            echo "<div class='alert alert-$type' role='alert'>$message</div>";
    
            unset($_SESSION['flash_message']);
        }
    }

    public function setInputData($input, $data) 
    {
        $_SESSION['inputs'][$input] = $data;
    }

    public function getInputData($input)
    {
        if (isset($_SESSION['inputs'][$input])) {
            return $_SESSION['inputs'][$input];
        }
        return '';    
    }
}
