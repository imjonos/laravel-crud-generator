<?php

namespace Nos\CRUD\Services;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Nos\CRUD\Repositories\BaseRepository;

abstract class BaseService
{
    protected string $repositoryClass = BaseRepository::class;
    private ?BaseRepository $repository = null;

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

    /**
     * @throws BindingResolutionException
     */
    public function getRepository(): BaseRepository
    {
        if (!$this->repository) {
            $this->repository = app()->make($this->repositoryClass);
        }

        return $this->repository;
    }

    /**
     * @throws BindingResolutionException
     */
    public function update(int $modeId, array $data): bool
    {
        return $this->getRepository()->update($modeId, $data);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Model
    {
        $model = $this->getRepository()->create($data);

        if (!$model) {
            throw new Exception(trans('exceptions.price_offer_view_not_created'));
        }

        return $model;
    }

    /**
     * @throws BindingResolutionException
     */
    public function delete(int $modeId): bool
    {
        return $this->getRepository()->delete($modeId);
    }
}
