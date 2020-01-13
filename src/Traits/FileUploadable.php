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
    	foreach($request->get('mediaCollections') as $key => $files) {
    		foreach ($files as $value) {
    			$model->addMedia(storage_path('app/' . $value['path']))->toMediaCollection($key);
    		}
    	}
    }
}
