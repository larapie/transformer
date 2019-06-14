<?php


namespace Larapie\Transformer\Tests;


use Illuminate\Support\Collection;
use Larapie\Transformer\Tests\Classes\HelloKitty;
use Larapie\Transformer\Tests\Classes\HelloKittyTransformer;
use Orchestra\Testbench\TestCase;

class TransformerTest extends TestCase
{

    public function testTransformerWithResource()
    {
        $model = HelloKitty::create();
        $transformer = new HelloKittyTransformer($model);
        $this->assertEquals($model->toArray(), $transformer->toArray());
    }

    public function testTransformerWithCollection()
    {
        $collection = new Collection();

        for ($i = 0; $i < $count = 5; $i++) {
            $collection->add(HelloKitty::create());
        }

        $transformer = HelloKittyTransformer::collection($collection);
        $data = $transformer->jsonSerialize();

        $this->assertCount($count, $data);
        $this->assertEquals($data[0], HelloKitty::create()->toArray());
    }

    public function testTransformerRelationWithResource()
    {
        $model = HelloKitty::create();
        $transformer = new HelloKittyTransformer($model);
        $transformer->include('friend');

        $this->assertArrayNotHasKey('friends', $transformer->toArray());
        $this->assertArrayHasKey('friend', $transformer->toArray());
        $this->assertEquals($model->friend->toArray(), $transformer->toArray()['friend']);
    }

    public function testWrongRelationThrowsException(){
        $model = HelloKitty::create();
        $transformer = new HelloKittyTransformer($model);
        $this->expectException(\RuntimeException::class);
        $transformer->include('lmqsdgqmsgj');
    }

    public function testTransformerRelationWithCollection()
    {
        $collection = new Collection();

        for ($i = 0; $i < $count = 5; $i++) {
            $collection->add($model = HelloKitty::create());
        }
        $transformer = HelloKittyTransformer::collection($collection);
        $transformer->include('friend');
        $transformer->additional(
            ["testing" => "here"]
        );
        $data = $transformer->jsonSerialize();

        $this->assertCount($count, $data);

        $modelWithFriend = tap($model->toArray(), function (&$value) use ($model) {
            $value['friend'] = $model->friend->toArray();
        });

        $this->assertEquals($data[0], $modelWithFriend);
    }
}