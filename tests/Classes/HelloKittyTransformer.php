<?php


namespace Larapie\Transformer\Tests\Classes;

use Larapie\Transformer\Transformer;

class HelloKittyTransformer extends Transformer
{
    public $relations = [
        "friend" => HelloKittyFriendTransformer::class
    ];

    public function transform(HelloKitty $model)
    {
        return [
            "first_name" => $model->first_name,
            "last_name" => $model->last_name
        ];
    }

    public function includeFriend(HelloKittyFriend $friend){
        return new HelloKittyFriendTransformer($friend);
    }
}