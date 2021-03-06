<?php

use App\Http\Controllers\Admin\TaskController as TaskAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('welcome');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::group(['middleware' => ['auth', 'save_last_action_timestamp']], function () {
    // Route::redirect('/', 'welcome');
    Route::get('welcome', [App\Http\Controllers\PageController::class, 'welcome'])->name('welcome');
    Route::get('consultation', [App\Http\Controllers\PageController::class, 'consultation'])->name('consultation');
    Route::get('/checklists/{checklist}', [App\Http\Controllers\User\ChecklistController::class, 'show'])->name('user.checklists.show');
    Route::get('/tasklist/{listType}', [App\Http\Controllers\User\ChecklistController::class, 'taskList'])->name('user.checklists.taskList');


    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'is_admin'], function () {
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
        Route::resource('checklist_groups', App\Http\Controllers\Admin\ChecklistGroupController::class);
        Route::resource('checklist_groups.checklists', App\Http\Controllers\Admin\ChecklistController::class);
        Route::resource('checklists.tasks', App\Http\Controllers\Admin\TaskController::class);

        Route::post('re-order-position', [TaskAdminController::class, 'reOrderPosition'])->name('reOrderPosition');
    });
});
