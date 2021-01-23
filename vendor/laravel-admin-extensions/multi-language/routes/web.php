<?php

use KevinSoft\MultiLanguage\Http\Controllers\MultiLanguageController;

Route::post('/locale', MultiLanguageController::class.'@locale');
Route::get('auth/login', MultiLanguageController::class.'@getLogin');
