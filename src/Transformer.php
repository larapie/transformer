<?php

namespace Larapie\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Larapie\Transformer\Traits\IncludesRelations;

class Transformer
{
    use IncludesRelations;

    protected $resource;

    /**
     * Transformer constructor.
     * @param $resource
     */
    protected function __construct($resource, array $options = [])
    {
        $this->resource = $resource;
        $this->compileIncludes($options);
    }

    /**
     * @param $resource
     * @return TransformerResource
     */
    public static function resource($resource)
    {
        $transformer = (new static($resource));
        return $transformer->transformResource($transformer->transform());
    }

    /**
     * @param $collection
     * @return TransformerCollection
     */
    public static function collection($collection)
    {
        $transformer = (new static($collection));
        return $transformer->transformCollection(new Collection($transformer->transform()), $transformer->getResourceType());
    }

    protected function buildCollection()
    {
        if (is_array($this->include) && !empty($this->include) && $this->resource instanceof Collection && $this->resource->isNotEmpty())
            $this->resource->loadMissing($this->include ?? []);

        $resources = [];
        $collection = ($this->resource);
        if ($collection instanceof Collection) {
            foreach ($collection as $resource) {
                $resources[] = (new static($resource, ["include" => $this->include]))->transform();
            }
        }
        return $resources;
    }

    protected function getResourceType()
    {
        if ($this->resource === null || (is_array($this->resource) && empty($this->resource)))
            return "null";
        else if (is_array($this->resource) && !empty($this->resource) && !$this->isAssoc($this->resource)) {
            return get_class($this->resource[0]);
        } else if ($this->resource instanceof Collection || $this->resource instanceof \Illuminate\Support\Collection)
            return get_class($this->resource->first());
        return get_class($this->resource);
    }

    protected function isAssoc(array $arr)
    {
        if (array() === $arr)
            return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    protected function transformCollection(Collection $collection , string $resourceType)
    {
        return new TransformerCollection($collection,$resourceType);
    }

    protected function transformResource($resource)
    {
        return new TransformerResource($resource);
    }

    protected function transform()
    {
        if ($this->resource instanceof Collection)
            return $this->buildCollection();
        return array_merge($this->buildArray(), $this->compileRelationData());
    }

    protected function buildArray()
    {
        if (method_exists($this, 'toArray'))
            return $this->toArray($this->resource);
        return [];
    }

    protected function serialize()
    {
        return $this->transform();
    }


}