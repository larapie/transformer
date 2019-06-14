<?php


namespace Larapie\Transformer;

use ArrayAccess;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use JsonSerializable;
use Larapie\Transformer\Traits\IncludesRelation;
use Larapie\Transformer\Traits\ProxyInterfaces;

abstract class Transformer implements ArrayAccess, JsonSerializable, Responsable, UrlRoutable
{
    use ProxyInterfaces, IncludesRelation;

    protected $input;

    /**
     * @var TResource
     */
    protected $resource;

    public function __construct($input)
    {
        if ($input instanceof Collection)
            throw new \RuntimeException("Cannot transform collections. Use the static collection() method");

        $this->input = $input;

        $this->boot();
    }

    protected function boot()
    {
        $this->resource = $this->createResource($this->input);
    }

    protected function createResource($input): TResource
    {
        $resource = method_exists($this, 'transform') ?
            array_merge($this->transform($input),$this->compileRelations()) :
            [];
        return new TResource($resource);
    }

    public function toArray(){
        return $this->jsonSerialize();
    }

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