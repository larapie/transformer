<?php


namespace Larapie\Transformer\Tests\Classes;


use Illuminate\Database\Eloquent\Model;

class HelloKittyFriend extends Model
{
    protected $fillable = ['first_name','last_name'];

    public static function create()
    {
        return new static([
            "first_name" => "friendly",
            "last_name" => "kitty"
            ]);
    }
}