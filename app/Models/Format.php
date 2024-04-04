<?php 

namespace App\Models;


class Format extends Model
{ 

    protected static $table = 'formats';

    public function __construct(protected $attributes = [])
    {}

}