<?php


namespace Larapie\Transformer;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Larapie\Transformer\Traits\IncludesRelation;

class TCollection extends AnonymousResourceCollection
{
    use IncludesRelation;

    protected $input;

    public function __construct($resource, $collects)
    {
        $this->input= $resource;
        parent::__construct($resource, $collects);
    }

    protected function boot(){
        $this->collectResource($this->input);
    }

    protected function collectResource($resource)
    {
        if($resource instanceof Collection)
            $resource->loadMissing($this->resolveRelations());
        return tap(parent::collectResource($resource), function (){
            $this->collection->transform(function ($item, $key){
                if($item instanceof Transformer){
                    if(!empty($relations = $this->resolveRelations()))
                        $item->include(...$relations);
                    return $item->getBaseResource();
                }
            });
        });
    }

}