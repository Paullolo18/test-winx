<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

// Página inicial com registro, login e informações da API
Route::get('/', [ViewController::class, 'welcome'])->name('welcome');

// Página de registro
Route::get('/register', [ViewController::class, 'register'])->name('register');

// Página de login
Route::get('/login', [ViewController::class, 'login'])->name('login');

// Dashboard para upload e listagem
Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');