<?php
/**
 * Eugeny Nosenko 2021
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * Trait Crudable used for Models
 * @package Nos\CRUD\Traits
 */
trait Crudable
{
    /**
     * Search by scopes (new version)
     * @param $query
     * @param array $fields fields for selection
     * @param array $requestData
     * @return Builder
     */
    public function scopeOfSearch($query, array $fields = ['*'], array $requestData = []): Builder
    {
        $excludedScopeKeys = ['order_column'];
        $query->select($fields);
        foreach ($requestData as $key => $value) {
            if (!in_array($key, $excludedScopeKeys) && method_exists($this, 'scopeOf' . Str::camel($key))) {
                $query->{'of' . ucfirst(Str::camel($key))}($value);
            }
            if (!empty($requestData['order_column'])) {
                $query->ofOrderColumn(
                    $requestData['order_column'],
                    (!empty($requestData['order_direction']) ? $requestData['order_direction'] : 'ASC')
                );
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        return $query;
    }

    /**
     * Scope for ordering results
     * @param $query
     * @param string $column
     * @param string $direction
     * @return mixed
     */
    public function scopeOfOrderColumn($query, string $column, string $direction = 'ASC')
    {
        return $query->orderBy($column, $direction);
    }

    /**
     * Get columns list of model
     * @return array
     */
    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * Sync Many To Many Data
     *
     * @param FormRequest $request
     * @param Model $model
     * @param array $keys
     */
    public function syncData(FormRequest $request, Model $model, array $keys = []): void
    {
        foreach ($keys as $key) {
            if ($request->exists($key)) {
                $collection = collect($request->get($key, []));
                $ids = $collection->pluck(['id'])->toArray();
                $model->{Str::camel($key)}()->sync($ids);
            }
        }
    }
}
