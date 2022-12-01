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

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Nos\CRUD\Http\Requests\UploadRequest;

final class MediaController extends Controller
{
    public function upload(UploadRequest $request): Response
    {
        $path = $request->file('file')->store('files');

        return response([
            'path' => $path
        ]);
    }
}
