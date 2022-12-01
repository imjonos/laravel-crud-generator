<?php

namespace Nos\CRUD\Services;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig;
use Spatie\MediaLibrary\HasMedia\HasMedia;

final class MediaService
{
    /**
     * Add File to MediaCollection
     *
     * @param HasMedia $model
     * @param string $collectionName
     * @param array $fileValidationRules
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DiskDoesNotExist
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function uploadCollection(HasMedia $model, string $collectionName, array $fileValidationRules = []): void
    {
        $mediaCollection = $this->getMediaCollectionFromRequest($collectionName, $fileValidationRules);
        if ($mediaCollection) {
            foreach ($mediaCollection['files'] as $file) {
                $model->addMedia(storage_path('app/' . $file['path']))->toMediaCollection($mediaCollection['name']);
            }
            foreach ($mediaCollection['removedFiles'] as $file) {
                $model->deleteMedia($file['id']);
            }
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getMediaCollectionFromRequest(string $collectionName, array $fileValidationRules = []): ?array
    {
        $result = null;
        if (request()->exists($collectionName)) {
            $rules = [];
            $rules[$collectionName] = ['nullable', 'array'];
            $rules[$collectionName . '.files'] = ['required', 'array'];
            $rules[$collectionName . '.removedFiles'] = ['nullable', 'array'];
            $rules[$collectionName . '.removedFiles.*'] = ['required', 'array'];
            $rules[$collectionName . '.removedFiles.*.id'] = ['required', 'integer'];

            foreach ($fileValidationRules as $key => $validationRule) {
                $rules[$collectionName . '.files.*.' . $key] = $validationRule;
            }

            request()->validate(
                $rules
            );
            $result = request()->get($collectionName, null);
        }

        return $result;
    }
}
