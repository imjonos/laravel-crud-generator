<?php

namespace Nos\CRUD\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Nos\CRUD\Repositories\BaseRepository;

abstract class BaseService
{
    protected string $repositoryClass = BaseRepository::class;
    private BaseRepository $repository;

    public function __construct()
    {
        $this->repository = app()->make($this->repositoryClass);
    }

    public function search(
        array $filter = [],
        array $fields = ['*'],
        array $with = [],
        int $limit = 10
    ): LengthAwarePaginator {
        return $this->repository
            ->query()
            ->ofSearch($fields, $filter)
            ->with($with)
            ->paginate($limit);
    }

    public function update(int $modeId, array $data): bool
    {
        return $this->repository->update($modeId, $data);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Model
    {
        $model = $this->repository->create($data);

        if (!$model) {
            throw new Exception(trans('exceptions.price_offer_view_not_created'));
        }

        return $model;
    }

    public function delete(int $modeId): bool
    {
        return $this->repository->delete($modeId);
    }
}
