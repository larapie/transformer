<?php

namespace Larapie\Transformer;

use Illuminate\Http\Resources\Json\JsonResource;

class TransformerResource extends JsonResource
{
    public function toArray($request = null)
    {
       return array_merge([
           "hellothere" => 5],
           $this->resource);
    }

}