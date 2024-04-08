<?php 

namespace App;

use App\Models\User;

class Auth
{
    public static function session(User $user)
    {
        session_start();
        $_SESSION['id'] = $user->id;
        $_SESSION['username'] = $user->username;

    }

    public static function check()
    {
        session_start();
        if (isset($_SESSION['id'])) {
            return true;
        }
        return false;
    }

    public static function id()
    {
        return $_SESSION['id'];
    }

    public static function username()
    {
        return $_SESSION['username'];
    }
}