<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ImportRequest
 * @package CodersStudio\CRUD
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
            'import_file' => 'required|max:50000|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
    }
}
