<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait FileUploadable
{
	/**
	 * Add File to MediaCollection
	 * @param  Request $request
	 * @param  Model   $model
	 * @return void
	 */
    protected function upload(Request $request, Model $model, string $collectionName = 'media_collection'):void
    {
	    $mediaCollection = $request->get($collectionName, ['name' => '', 'files' => []]);
	    
    	foreach($mediaCollection['files'] as $key => $file) {
    		$model->addMedia(storage_path('app/' . $file['path']))->toMediaCollection($mediaCollection['name']);
    	}
	    foreach($mediaCollection['removedFiles'] as $key => $file) {
    		$model->deleteMedia($file['id']);
    	}
    }
}
