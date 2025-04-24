<?php
namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UniversalFilter
{
    protected Request $request;
    protected Builder $builder;
    protected array $allowedFilters;

    public function __construct(Request $request, array $allowedFilters = [])
    {
        $this->request = $request;
        $this->allowedFilters = $allowedFilters;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
    
        if ($this->request->has('q')) {
            $this->builder->where('title', 'like', '%' . $this->request->query('q') . '%');
        }
    
        $filters = $this->request->query('filters', []);
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
        }
    
        foreach ($filters as $key => $value) {
            if (in_array($key, $this->allowedFilters)) {
                $this->builder->where($key, $value);
            }
        }
    
        return $this->builder;
    }
    
}
