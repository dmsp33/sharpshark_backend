<?php

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Certificado;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertMail;
use App\Events\CopyleaksSent;

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
    return view('welcome');
});

Route::get('/t', function () {
    broadcast(new CopyleaksSent('prueba' , 'prueba', 'prueba', 'prueba', 'prueba'));
});

Route::get('/claim', function () {
    return view('mails.claim');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('documentos', App\Http\Controllers\DocumentoController::class);

Route::resource('certificados', App\Http\Controllers\CertificadoController::class);

Route::get('auth/google' , [App\Http\Controllers\API\AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [App\Http\Controllers\API\AuthController::class, 'handleGoogleCallback']);

Route::get('/test', function() {
    return PDF::loadView('certificados.showpdf', compact('certificado'))->stream('archivo.pdf');
});

Route::get('/data', function() {
    return App\Models\Documento::all();
});

Route::get('/testEmail' , function(){
   $data = App\Models\TeamInvitation::first();
   if(blank($data)) {
    $team = App\Models\Team::first();
    $data = $team->teamInvitations()->create([
        'email' => 'testmail',
        'name' => 'name tes',
    ]);
   }

   return (new App\Mail\API\TeamInvitation($data))->render();

   return view('mails.api.reset_password');
   return view('mails.firstv' , compact('user'));
});

Route::get('/sendEmail/{uuid}' , function($uuid){

    $user = App\Models\User::with('alerts')->where('uuid' , $uuid)->first();

    try {
        Mail::to($user->email)->send(new AlertMail($user));
    } catch (\Throwable $th) {
        return response()->json(['Message' => 'No se pudo enviar el correo']);
    }

    return response()->json(['Message' => 'Correo Enviado']);




});


Route::resource('teams', App\Http\Controllers\TeamController::class);

Route::resource('teamInvitations', App\Http\Controllers\TeamInvitationController::class);