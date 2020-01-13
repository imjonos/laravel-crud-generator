<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package CodersStudio\CRUD
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
    public function scopeOfName($query,$value)
    {
        return $query->where('name','=',$value);
    }
}
