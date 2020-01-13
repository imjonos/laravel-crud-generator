<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Traits;

use Illuminate\Support\Str;

/**
 * Trait Crudable used for Models
 * @package CodersStudio\CRUD\Traits
 */
trait Crudable
{
    /**
     * Search by scopes
     * @param array $fields fields for selection
     * @param array $requestData
     * @return Builder
     */
    public function search(array $fields, array $requestData)
    {
        $excludedScopeKeys = ['order_column'];
        $result = $this->select($fields);
        foreach ($requestData as $key=>$value) {
            if (!in_array($key,$excludedScopeKeys) && method_exists($this,'scopeOf'.Str::camel($key))) {
                $result = $result->{'of'.ucfirst(Str::camel($key))}($value);
            }
            if (!empty($requestData['order_column'])) {
                $result = $result->ofOrderColumn($requestData['order_column'], (!empty($requestData['order_direction']) ? $requestData['order_direction'] : 'ASC'));
            }
        }
        return $result->orderBy('id','desc');
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
        return $query->orderBy($column,$direction);
    }

    /**
     * Get columns list of model
     * @return array
     */
    public function getTableColumns() : array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
