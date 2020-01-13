<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRequest
 * @package CodersStudio\CRUD
 */
class UploadRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
    * rules
    */
    public function rules()
    {
        return config('crud.upload_rules');
    }
}
