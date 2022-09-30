<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ImportRequest
 * @package Nos\CRUD
 */
class ImportRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize()
    {
        return true;
    }

    /**
     * rules
     */
    public function rules()
    {
        return [
            'import_file' => 'required|max:50000|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.oasis.opendocument.spreadsheet,text/plain',
        ];
    }
}
