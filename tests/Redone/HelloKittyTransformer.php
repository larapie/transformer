<?php


namespace Larapie\Transformer\Tests\Redone;


use Larapie\Transformer\Redone\RedoneTransformer;
use Larapie\Transformer\Tests\Classes\HelloKitty;
use Larapie\Transformer\Tests\Classes\HelloKittyFriend;
use Larapie\Transformer\Tests\Classes\HelloKittyFriendTransformer;

class HelloKittyTransformer extends RedoneTransformer
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