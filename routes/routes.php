<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::namespace('\Nos\CRUD\Http\Controllers')->group(function () {
        Route::post('/upload', 'MediaController@upload');
    });
});
