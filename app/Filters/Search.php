<?php

namespace App\Filters;

use Samushi\QueryFilter\Filter;

class Search extends Filter
{

    /**
     * @var array
     */
    private $search;

    public function __construct(array $search = ['query'])
    {
        $this->search = $search;
    }
    /**
     * Search Result by whereName
     * @param $builder
     * @return mixed
     */
    protected function applyFilter($builder)
    {
        // if you wanna search with realtionship [name, 'posts.title']
        return $builder->whereLike($this->search, request()->get($this->fillterName()));
    }
}
