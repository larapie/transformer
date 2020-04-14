<?php


namespace Larapie\Transformer\Traits;


trait ProxyInterfaces
{
    public function offsetExists($offset)
    {
        return $this->resource->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->resource->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->resource->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->resource->offsetUnset($offset);
    }

    public function toResponse($request = null)
    {
        return $this->resource->toResponse($request);
    }

    public function getRouteKey()
    {
        return $this->resource->getRouteKey();
    }

    public function getRouteKeyName()
    {
        return $this->resource->getRouteKeyName();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return void
     *
     * @throws \Exception
     */
    public function resolveRouteBinding($value, $field=null)
    {
        $this->resource->resolveRouteBinding($value);
    }
    
     /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        $this->resource->resolveChildRouteBinding($childType, $value, $field);
    }

    public function jsonSerialize()
    {
        return $this->resource->jsonSerialize();
    }
}
