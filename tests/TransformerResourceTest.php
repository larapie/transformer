<?php

namespace Larapie\Transformer\Tests;

use Illuminate\Database\Eloquent\Collection;
use Larapie\Transformer\Tests\Classes\AModel;
use Larapie\Transformer\Tests\Classes\AModelTransformer;
use Larapie\Transformer\TransformerResource;
use Orchestra\Testbench\TestCase;

class TransformerResourceTest extends TestCase
{
    protected $data = [
        "test" => 5,
        "hello" => "world"
    ];

    public function testTransformerResource()
    {
        $transformerResource = new TransformerResource([
            "test" => 5,
            "hello" => "world"
        ]);

        $this->assertEquals([
            "test" => 5,
            "hello" => "world"
        ], $transformerResource->toArray());
    }

    public function testModelTransformation()
    {
        $model = new AModel($this->data);
        $transformer = AModelTransformer::resource($model);
        $this->assertEquals($transformer, $this->data);
    }

    public function testCollectionTransformation()
    {
        $model = new AModel($this->data);
        $model2 = new AModel($this->data);

        $collection = new Collection([$model, $model2]);
        $transformer = AModelTransformer::collection($collection);
        $data = $transformer->resolve()['data']->toArray();
        $this->assertEquals($transformer->jsonSerialize(), $this->data);
    }

    public function testTestingisWorking()
    {
        $this->assertTrue(true);
    }
}