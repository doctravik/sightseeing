<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class Filters
{
    /**
     * @var Builder.
     */
    protected $builder;

    /**
     * Allowed filters.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new instance of Filters.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Apply filters.
     *
     * @return Builder
     */
    public function apply()
    {
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Get all filters from browser request.
     *
     * @return array
     */
    protected function getFilters()
    {
        return request()->only($this->filters);
    }
}
