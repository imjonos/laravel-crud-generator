<?php
/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

/**
 * Eugeny Nosenko 2022
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Http\Controllers;

use Illuminate\Routing\Controller;
use Nos\CRUD\Http\Requests\UploadRequest;

class MediaController extends Controller
{
    public function upload(UploadRequest $request)
    {
        if (!empty(config('crud.upload_extensions'))) {
            $ext = $request->file('file')->getClientOriginalExtension();
            if (!in_array($ext, config('crud.upload_extensions'))) {
                abort(422, 'Unsopported file format');
            }
        }
        $path = $request->file('file')->store('files');

        return response([
            'path' => $path
        ]);
    }
}
