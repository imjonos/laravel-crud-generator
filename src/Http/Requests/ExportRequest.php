<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ExportRequest
 * @package Nos\CRUD
 */
final class ExportRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * rules
     */
    public function rules(): array
    {
        return [
        ];
    }
}
