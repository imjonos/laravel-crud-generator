<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package Nos\CRUD
 */
class Setting extends Model
{
    protected $fillable = [
        'name',
        'value',
        'enabled',
    ];

    /**
     * Scope for filtering by name
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeOfName($query, $value)
    {
        return $query->where('name', '=', $value);
    }
}
