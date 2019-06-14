<?php


namespace Larapie\Transformer\Tests\Classes;


use Larapie\Transformer\Transformer;

class AModelTransformer extends Transformer
{
    public function transform(AModel $model)
    {
        return [
            "test" => $model->test,
            "hello" => $model->hello
        ];
    }
}