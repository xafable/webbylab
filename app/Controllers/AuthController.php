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

    public function register(Request $request)
    {
        
    }

    
    public function logout() {
        // End session and redirect to login page
        session_start();
        session_destroy();
       
        Response::json([
            'message' => 'Logout successful'
        ]);
      
    }
}