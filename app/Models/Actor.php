<?php 

namespace App\Models;

class Actor extends Model
{

    protected static $table = 'actors';

    public function __construct(protected $attributes = [])
    {}
}