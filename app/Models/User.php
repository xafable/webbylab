<?php 

namespace App\Models;

class User extends Model
{
    protected static $table = 'users';

    public function __construct(protected $attributes = [])
    {}


}