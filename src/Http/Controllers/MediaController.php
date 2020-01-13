<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Http\Controllers;

use CodersStudio\CRUD\Http\Requests\UploadRequest;
use Illuminate\Routing\Controller;

class MediaController extends Controller
{
    public function upload(UploadRequest $request)
    {
        if (!empty(config('crud.upload_extensions'))) {
            $ext = $request->file('file')->getClientOriginalExtension();
            if (!in_array($ext,config('crud.upload_extensions'))) {
                abort(422, 'Unsopported file format');
            }
        }
        $path = $request->file('file')->store('files');
        return response([
            'path' => $path
        ]);
    }
}
