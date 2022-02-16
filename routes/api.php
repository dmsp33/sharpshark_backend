<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stevebauman\Location\Facades\Location;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('user-exists', [App\Http\Controllers\API\AuthController::class, 'userExists']);
Route::post('password/forgot', [App\Http\Controllers\API\AuthController::class, 'forgotPassword']);
Route::post('password/reset', [App\Http\Controllers\API\AuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| API Routes CopyLeaks
|--------------------------------------------------------------------------
*/
Route::get('/getAuthKey', [App\Http\Controllers\CopyleakController::class, 'getAuthKey']);
Route::post('/copyleaks/webhook/{status?}', [App\Http\Controllers\CopyleakController::class, 'Webhook'])->name('copyleaks.webhook');
Route::post('/console/copyleaks/submitFile', [App\Http\Controllers\CopyleakController::class, 'SubmitFile'])->name('console.copyleaks.submit');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/copyleaks/submitFile', [App\Http\Controllers\CopyleakController::class, 'SubmitFile'])->name('copyleaks.submit');
    Route::post('/copyleaks/fakeBroadcast', [App\Http\Controllers\CopyleakController::class, 'fakeBroadcast'])->name('copyleaks.fakeBroadcast');
    
    Route::post('documentos/update-version/{uuid}', [App\Http\Controllers\API\DocumentoAPIController::class, 'update_version']);
    Route::resource('documentos', App\Http\Controllers\API\DocumentoAPIController::class);
    Route::get('documentos-eliminados', [App\Http\Controllers\API\DocumentoAPIController::class, 'deleted']);
    Route::delete('documentos-eliminados/{uuid}', [App\Http\Controllers\API\DocumentoAPIController::class, 'forceDestroy']);
    Route::post('documentos-restaurar/{uuid}', [App\Http\Controllers\API\DocumentoAPIController::class, 'restore']);
    Route::get('protected-documents', [App\Http\Controllers\API\DocumentoAPIController::class, 'protectedTracking']);
    Route::get('protected-not-tracking-documents', [App\Http\Controllers\API\DocumentoAPIController::class, 'protectedNotTracking']);
    Route::get('wfprotected-documents', [App\Http\Controllers\API\DocumentoAPIController::class, 'wfprotectedTracking']);
    Route::get('wfprotected-not-tracking-documents', [App\Http\Controllers\API\DocumentoAPIController::class, 'wfprotectedNotTracking']);

    Route::get('disputas' , [App\Http\Controllers\API\DisputeController::class, 'index']);
    Route::post('disputas' , [App\Http\Controllers\API\DisputeController::class, 'store']);
    Route::get('disputas/{id}' , [App\Http\Controllers\API\DisputeController::class, 'show']);
    Route::put('disputas/{id}' , [App\Http\Controllers\API\DisputeController::class, 'update']);
    Route::delete('disputas/{id}' , [App\Http\Controllers\API\DisputeController::class, 'destroy']);
    Route::post('disputas/sendClaim' , [App\Http\Controllers\API\DisputeController::class, 'sendClaim']);
    
    Route::resource('certificados', App\Http\Controllers\API\CertificadoAPIController::class, ['except' => ['show']]);

    Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('Alert/getAlerts' , [App\Http\Controllers\API\AlertController::class , 'getAlerts'])->name('actual.alerts');
    Route::put('Alert/update/{id}' , [App\Http\Controllers\API\AlertController::class , 'update'])->name('update.alerts');
    Route::get('Alert/getActualAlerts' , [App\Http\Controllers\API\AlertController::class , 'getActualAlerts']);
    Route::get('Alert/getNotActualAlerts' , [App\Http\Controllers\API\AlertController::class , 'getNotActualAlerts']);
    Route::get('Alert/wfgetActualAlerts' , [App\Http\Controllers\API\AlertController::class , 'getActualAlertswf']);
    Route::get('Alert/wfgetNotActualAlert' , [App\Http\Controllers\API\AlertController::class , 'getNotActualAlertswf']);
    Route::get('Alert/Location/{alerta_id}/{documento_id}' , [App\Http\Controllers\API\AlertController::class , 'showForm']);
    
    Route::apiResource('teams', App\Http\Controllers\API\TeamAPIController::class);
    Route::apiResource('teams.members', App\Http\Controllers\API\TeamMemberAPIController::class)->only(['store', 'destroy']);
    Route::get('team-invitations/{invitation}', [App\Http\Controllers\API\TeamInvitationAPIController::class, 'accept']);
    Route::delete('team-invitations/{invitation}', [App\Http\Controllers\API\TeamInvitationAPIController::class, 'destroy']);

    Route::post('payment', [App\Http\Controllers\API\PaymentAPIController::class , 'payment']);
});

Route::get('/certificados/{uuid}', [App\Http\Controllers\API\CertificadoAPIController::class, 'show'])->name('certificados.show');
Route::post('/uploadImage' , [App\Http\Controllers\API\CertificadoAPIController::class, 'uploadImages']);


Route::get('/collect/{userid}', [App\Http\Controllers\API\AlertBotController::class, 'collect'])->where('userid', '[0-9]+');
Route::get('/send/{userid}', [App\Http\Controllers\API\AlertBotController::class, 'send']);







