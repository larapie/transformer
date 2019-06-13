<?php


namespace Larapie\Transformer\Traits;


use Larapie\Transformer\Contracts\Transformable;

trait IncludesRelations
{
    protected $include = [];

    /**
     * @return array
     */
    protected function parseRequestedRelations($request): array
    {
        if (isset($request->include) && is_string($request->include)) {
            return explode(',', $request->include);
        }
        return [];
    }

    protected function compileIncludes($options)
    {
        $include = $options['include'] ?? null;
        $include = array_merge($include ?? $this->parseRequestedRelations(request()), $this->include ?? []);
        $this->include = empty($include) ? null : $include;
    }

    public function addRelation(string $relation)
    {
        if (!array_key_exists($relation, $this->relations()))
            throw new \RuntimeException("The relation $relation wich you are trying to add is not specified on the transformer " . static::class);
        $this->include[] = $relation;
    }

    public function overrideRelations(array $relations)
    {
        $this->include = $relations;
    }

    protected function relations(): array
    {
        return [];
    }

    protected function compileRelationData(): array
    {
        $relations = $this->relations();
        $relationData = [];
        foreach ($this->include ?? [] as $includedRelation) {
            if (array_key_exists($includedRelation, $relations))
                $relationData[$includedRelation] = $this->resolveTransformerForRelation($relations[$includedRelation], $includedRelation)->serialize();
        }
        return $relationData;
    }

    protected function resolveTransformerForRelation($transformer, $relation): ?Transformable
    {
        if (is_string($transformer)) {
            if ($this->resource->$relation !== null)
                return new $transformer($this->resource->$relation);
            return null;
        } elseif ($transformer instanceof Transformable)
            return $transformer;
        return null;
    }
}