<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::namespace('\CodersStudio\CRUD\Http\Controllers')->group(function () {
        Route::post('/upload', 'MediaController@upload');
    });
});
