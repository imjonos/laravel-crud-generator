<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;

use Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Multitenantable
 * @package Nos\CRUD\Traits
 */
trait Multitenantable
{
    /**
     * Save and check if user owner of row
     */
    protected static function bootMultitenantable()
    {
        static::creating(function ($model) {
            if (
                empty($model->user_id) ||
                (
                    Auth::check() &&
                    !Auth::user()->hasAnyRole('Administrator|administrator|manager')
                )
            ) {
                $model->user_id = Auth::id();
            }
        });

        static::addGlobalScope('created_by', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasAnyRole('Administrator|administrator|manager')) {
                $builder->where('user_id', Auth::id());
            }
        });
    }
}
