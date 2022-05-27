<?php

namespace Nos\CRUD\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Nos\BaseService\BaseService as Service;

abstract class BaseService extends Service
{
    /**
     * @throws BindingResolutionException
     */
    public function search(
        array $filter = [],
        array $fields = ['*'],
        array $with = [],
        int $limit = 10
    ): LengthAwarePaginator {
        return $this->getRepository()
            ->query()
            ->ofSearch($fields, $filter)
            ->with($with)
            ->paginate($limit);
    }
}
