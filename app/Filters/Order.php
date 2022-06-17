<?php

namespace App\Filters;

use Samushi\QueryFilter\Filter;

class Order extends Filter
{


    private $by;
    /**
     * @var string
     */
    private $sort;

    public function __construct(string $by = 'id', $sort = 'desc')
    {
        $this->by = $by;
        $this->sort = $sort;
    }
    /**
     * Search Result by whereName
     * @param $builder
     * @return mixed
     */
    protected function applyFilter($builder)
    {
        // if you wanna search with realtionship [name, 'posts.title']
//        return $builder->whereLike($this->search, request()->get($this->fillterName()));
        return $builder->orderBy($this->by, $this->sort);
    }
}
