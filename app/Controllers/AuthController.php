<?php 

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use App\Http\Request;
use App\Http\Response;
use App\Services\DataBaseClient;
use App\Utils;

class AuthController
{
    public function __construct(private DataBaseClient $dbClient)
    {}


    public function index()
    {
        Response::View('login');

    }
    public function login(Request $request)
    {  
          $username = $request->username;
          $password = $request->password;

        

          $users = User::query()
                       ->where('username', '=', $username)
                       ->where('password', '=', md5($password))
                       ->get();
              


          if(!empty($users)) {

              $user = $users[0];

              Auth::session($user);

              Response::redirect('/movies');
          }   
          else {
              Utils::setFlashMessage('Wrong username or password');

              Response::redirect('/login');
          }         

    }

    public function showRegister()
    {
        Response::View('register');
    }

    public function register(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        if(empty($username) || empty($password)) {

            Utils::setFlashMessage('Username and password are required');

            Response::redirect('/register');
        }

        $userExists = User::query()
        ->dbClient()
        ->exists('username', $username);


        if($userExists) {
            Utils::setFlashMessage('User already exists');

            Response::redirect('/register');
        }


        $user = User::query()
        ->create([
            'username' => $username,
            'password' => md5($password)
        ]);


        Auth::session($user);

        Response::redirect('/movies');
        
    }

    
    public function logout() {
        session_start();
        session_destroy();
       
        Response::json([
            'message' => 'Logout successful'
        ]);
      
    }
}