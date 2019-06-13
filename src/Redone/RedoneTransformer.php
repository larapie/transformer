<?php


namespace Larapie\Transformer\Redone;

use ArrayAccess;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use JsonSerializable;
use Larapie\Transformer\Redone\Traits\IncludesRelation;
use Larapie\Transformer\Redone\Traits\ProxyInterfaces;

abstract class RedoneTransformer implements ArrayAccess, JsonSerializable, Responsable, UrlRoutable
{
    use ProxyInterfaces, IncludesRelation;

    protected $input;

    /**
     * @var TResource
     */
    protected $resource;

    public function __construct($input)
    {
        $this->boot($input);
    }

    protected function boot($input)
    {
        if ($input instanceof Collection)
            throw new \RuntimeException("Cannot transform collections. Use the static collection() method");

        $this->input = $input;
        $this->resource = $this->createResource($input);
    }

    protected function createResource($input): TResource
    {
        $resource = method_exists($this, 'transform') ?
            array_merge($this->transform($input),$this->resolveRelations()) :
            [];
        return new TResource($resource);
    }

    public function toArray(){
        return $this->jsonSerialize();
    }

    //TODO remove this & access on collection through reflection
    public function getBaseResource(){
        return $this->resource;
    }

    public static function resource($resource)
    {
        return (new static($resource));
    }

    public static function collection($collection)
    {
        return new TCollection($collection, static::class);
    }



}