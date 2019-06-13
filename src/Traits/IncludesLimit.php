<?php


namespace Larapie\Transformer\Traits;


trait IncludesLimit
{
    protected $limit;

    /**
     * @return ?int
     */
    protected function parseRequestedLimit($request)
    {
        if (isset($request->limit) && is_numeric($request->limit)) {
            return (int)$request->limit;
        }
        return null;
    }

    protected function compileLimit(array $options)
    {
        $limit = $options['limit'] ?? null;
        $this->limit = $limit ?? $this->parseRequestedLimit(request());
    }

    public function overrideLimit(int $limit)
    {
        $this->limit = $limit;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    protected function applyLimitToCollection(&$collection){
        if ($this->getLimit() !== null)
            $collection = $collection->limit($this->getLimit());
        return $collection;
    }
}