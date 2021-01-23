<?php

use \Jxlwqq\FileManager\FileManagerController;

Route::get('file-manager', FileManagerController::class . '@index')
    ->name('file-manager-index');
Route::get('file-manager/download', FileManagerController::class . '@download')
    ->name('file-manager-download');
Route::delete('file-manager/delete', FileManagerController::class . '@delete')
    ->name('file-manager-delete');
Route::put('file-manager/move', FileManagerController::class . '@move')
    ->name('file-manager-move');
Route::post('file-manager/upload', FileManagerController::class . '@upload')
    ->name('file-manager-upload');
Route::post('file-manager/folder', FileManagerController::class . '@newFolder')
    ->name('file-manager-new-folder');
