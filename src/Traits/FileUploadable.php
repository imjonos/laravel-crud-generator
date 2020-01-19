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
    protected function upload(Request $request, Model $model):void
    {
	$mediaCollection = $request->get('media_collection', ['name' => '', 'files' => []]);
	    
    	foreach($mediaCollection['files'] as $key => $file) {
    		$model->addMedia(storage_path('app/' . $file['path']))->toMediaCollection($mediaCollection['name']);
    	}
    }
}
