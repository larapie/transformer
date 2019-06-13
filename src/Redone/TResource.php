<?php


namespace Larapie\Transformer\Redone;


use Illuminate\Http\Resources\Json\JsonResource;

class TResource extends JsonResource
{
    public function toArray($request = null)
    {
        return $this->resource;
    }
}