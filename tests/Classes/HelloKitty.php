<?php


namespace Larapie\Transformer\Tests\Classes;

use Illuminate\Database\Eloquent\Model;

class HelloKitty extends Model
{
    protected $fillable = ['first_name', 'last_name'];

    public $friend;

    public static function create()
    {
        return tap(new static([
            "first_name" => "hello",
            "last_name" => "kitty"
        ]), function (HelloKitty $model) {
            $model->friend = HelloKittyFriend::create();
        });
    }

    public function friend()
    {
        return $this->friend;
    }
}