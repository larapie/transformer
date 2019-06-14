<?php


namespace Larapie\Transformer\Tests\Classes;

use Larapie\Transformer\Transformer;

class HelloKittyFriendTransformer extends Transformer
{
    public function transform(HelloKittyFriend $model)
    {
        return [
            "first_name" => $model->first_name,
            "last_name" => $model->last_name
        ];
    }
}