<?php

namespace Nos\CRUD\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{
    public function find(int $id): ?Model;

    public function all(): Collection;

    public function query(): ?Builder;

    public function create(array $data): ?Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
