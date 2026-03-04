<?php

use App\Http\Controllers\UsuarioLocalController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

// Route::view('/', 'layouts.vue')->name('tela-login');
// Route::view('/login', 'layouts.vue')->name('tela-login');
Route::get('/', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::get('/login', [UsuarioLocalController::class, 'login'])->name('tela.login');
Route::post('/login', [\GapPay\Seguranca\Controllers\AutenticacaoController::class, 'login'])->name('login');
Route::get('/login/atualizar-senha', [\App\Http\Controllers\UsuarioLocalController::class, 'telaAtualizacaoSenhaViaHash'])->name('atualizar-senha');

Route::view('/atualizar-senha', 'layouts.vue');
Route::view('/atualizar-senha/{hash}', 'layouts.vue');
Route::view('/login/esqueci-email-senha', 'layouts.vue');