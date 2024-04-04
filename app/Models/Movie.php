<?php 

namespace App\Models;

class Movie extends Model
{

    protected static $table = 'movies';

    public function __construct(protected $attributes = [])
    {}
}


   