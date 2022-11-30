<?php

namespace Nos\CRUD\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Nos\CRUD\Http\Requests\UploadRequest;

final class MediaLibraryService
{
    /**
     * Add File to MediaCollection
     *
     * @param Request $request
     * @param Model $model
     * @param string $collectionName
     * @return void
     */
    public function uploadCollection(Request $request, Model $model, string $collectionName = 'media_collection'): void
    {
        if ($request->exists($collectionName)) {
            $mediaCollection = $request->get($collectionName, ['name' => '', 'files' => []]);

            foreach ($mediaCollection['files'] as $file) {
                $model->addMedia(storage_path('app/' . $file['path']))->toMediaCollection($mediaCollection['name']);
            }
            foreach ($mediaCollection['removedFiles'] as $file) {
                $model->deleteMedia($file['id']);
            }
        }
    }

    public function upload(UploadRequest $request): string
    {
        if (!empty(config('crud.upload_extensions'))) {
            $ext = $request->file('file')->getClientOriginalExtension();
            if (!in_array($ext, config('crud.upload_extensions'))) {
                abort(422, 'Unsopported file format');
            }
        }

        return $request->file('file')->store('files');
    }
}
