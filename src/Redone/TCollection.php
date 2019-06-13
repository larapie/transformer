<?php


namespace Larapie\Transformer\Redone;


use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TCollection extends AnonymousResourceCollection
{
    protected $input;

    public function __construct($resource, $collects)
    {
        $this->input= $resource;
        parent::__construct($resource, $collects);
    }

    protected function collectResource($resource)
    {
        return tap(parent::collectResource($resource), function (){
            $this->collection->transform(function ($item, $key){
                if($item instanceof RedoneTransformer){
                    return $item->getBaseResource();
                }
            });
        });
    }

    public function include(string ...$relation){
        return new static($this->input,$this->collects);
    }
}