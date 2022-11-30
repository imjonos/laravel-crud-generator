<?php
/**
 * Eugeny Nosenko 2021
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait FileUploadable
{
    /**
     * Add File to MediaCollection
     * @param Request $request
     * @param Model $model
     * @param string $collectionName
     * @return void
     */
    protected function upload(Request $request, Model $model, string $collectionName = 'media_collection'): void
    {
        if ($request->exists($collectionName)) {
            $mediaCollection = $request->get($collectionName, ['name' => '', 'files' => []]);

            foreach ($mediaCollection['files'] as $key => $file) {
                $model->addMedia(storage_path('app/' . $file['path']))->toMediaCollection($mediaCollection['name']);
            }
            foreach ($mediaCollection['removedFiles'] as $key => $file) {
                $model->deleteMedia($file['id']);
            }
        }
    }
}
