<?php


namespace Larapie\Transformer\Redone\Traits;


use Illuminate\Support\Collection;
use Larapie\Transformer\Redone\RedoneTransformer;
use Larapie\Transformer\Redone\TCollection;

trait IncludesRelation
{
    protected $relations = [];

    protected $default = [];

    protected $requestable = [];

    public function include(string ...$relations)
    {
        $this->default = array_merge($this->default, $relations);
        $this->resource = $this->createResource($this->input);
        return $this;
    }

    protected function resolveRelations()
    {
        $relations = [];

        foreach ($this->default as $defaultRelation) {
            if (is_string($defaultRelation)) {
                if (method_exists($this, $method = 'include' . ucfirst($defaultRelation))) {
                    $output = $relations[$defaultRelation] = $this->$method($this->input->$defaultRelation);

                    if ($output instanceof RedoneTransformer || $output instanceof TCollection) {
                        $output = $output->jsonSerialize();
                    }
                    $relations[$defaultRelation] = $output;
                } elseif (array_key_exists($defaultRelation, $this->relations)) {
                    if (class_exists($transformer = $this->relations[$defaultRelation])) {
                        if ($this->input->$defaultRelation === null)
                            $relations[$defaultRelation] = null;
                        else if ($this->input->$defaultRelation instanceof Collection) {
                            $relations[$defaultRelation] = $transformer::collection($this->input->$defaultRelation)->toArray();
                        } else {
                            $relations[$defaultRelation] = $transformer::resource($this->input->$defaultRelation)->toArray();
                        }

                    }
                }
            }
        }
        return $relations;
    }
}