<?php


namespace Larapie\Transformer\Tests\Classes;

use Larapie\Transformer\Redone\RedoneTransformer;

class HelloKittyFriendTransformer extends RedoneTransformer
{
    public function transform(HelloKittyFriend $model)
    {
        return [
            "first_name" => $model->first_name,
            "last_name" => $model->last_name
        ];
    }
}