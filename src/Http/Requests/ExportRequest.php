<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ExportRequest
 * @package CodersStudio\CRUD
 */
class ExportRequest extends FormRequest
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
        ];
    }
}
