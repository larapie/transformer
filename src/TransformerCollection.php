<?php

namespace Larapie\Transformer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransformerCollection extends ResourceCollection
{
    public function __construct($resource, ?string $collects = null)
    {
        if ($collects !== null)
            $this->collects = $collects;
        parent::__construct($resource);
    }


    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request=null)
    {
        return [$this->collection];
    }


}