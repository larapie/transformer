<?php


namespace Larapie\Transformer\Traits;


use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Larapie\Transformer\Transformer;
use Larapie\Transformer\TCollection;

trait IncludesRelation
{
    protected $relations = [];

    protected $include = [];

    protected $requestable = [];

    public function include(string ...$relations)
    {
        $this->include = array_merge($this->include, $relations);
        $this->boot();
        return $this;
    }

    public function requestable(string ...$relations)
    {
        $this->requestable = array_merge($this->requestable, $relations);
        $this->boot();
        return $this;
    }

    protected function parseRequestedRelations(): array
    {
        $request = Container::getInstance()->make('request');
        if (isset($request->include) && is_string($request->include)) {
            return explode(',', $request->include);
        }
        return [];
    }

    protected function resolveRequestedRelations()
    {
        $relations = [];

        foreach ($this->parseRequestedRelations() as $requestedRelation) {
            $requestedRelation = (string)$requestedRelation;
            if (in_array($requestedRelation, $this->requestable)) {
                $relations[] = $requestedRelation;
            }
        }
        return $relations;
    }

    protected function resolveIncludedRelations()
    {
        $relations = [];
        foreach ($this->include as $defaultRelation) {
            $defaultRelation = (string)$defaultRelation;
            $relations[] = $defaultRelation;
        }
        return $relations;
    }

    protected function resolveRelations()
    {
        return array_unique(array_merge($this->resolveIncludedRelations(), $this->resolveRequestedRelations()));
    }

    protected function compileRelations()
    {
        $relations = [];

        foreach ($this->resolveRelations() as $relation) {
            if (method_exists($this, $method = 'include' . ucfirst($relation))) {
                $output = $relations[$relation] = $this->$method($this->input->$relation);

                if ($output instanceof Transformer || $output instanceof TCollection) {
                    $output = $output->jsonSerialize();
                }
                $relations[$relation] = $output;
            } elseif (array_key_exists($relation, $this->relations)) {
                if (class_exists($transformer = $this->relations[$relation])) {
                    if ($this->input->$relation === null)
                        $relations[$relation] = null;
                    else if ($this->input->$relation instanceof Collection) {
                        $relations[$relation] = $transformer::collection($this->input->$relation)->toArray();
                    } else {
                        $relations[$relation] = $transformer::resource($this->input->$relation)->toArray();
                    }

                }
            } else {
                throw new \RuntimeException("no transformer or method specified for the relation");
            }
        }
        return $relations;
    }
}