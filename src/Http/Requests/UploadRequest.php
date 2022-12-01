<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRequest
 * @package Nos\CRUD
 */
final class UploadRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * rules
     */
    public function rules(): array
    {
        return config('crud.upload_rules');
    }
}
