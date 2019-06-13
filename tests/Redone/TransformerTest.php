<?php


namespace Larapie\Transformer\Tests\Redone;


use Illuminate\Support\Collection;
use Larapie\Transformer\Tests\Classes\HelloKitty;
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

    public function testTransformerRelationWithResource(){
        $model = HelloKitty::create();
        $transformer = new HelloKittyTransformer($model);
        $transformer->include('friend','friends');

        $this->assertArrayNotHasKey('friends',$transformer->toArray());
        $this->assertArrayHasKey('friend',$transformer->toArray());
        $this->assertEquals($model->friend->toArray(),$transformer->toArray()['friend']);
    }
}