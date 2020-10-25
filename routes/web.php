<?php

use App\Http\Controllers\albumController;
use App\Http\Controllers\artistaController;
use App\Http\Controllers\cancionesController;
use App\Http\Controllers\generoController;
use App\Http\Controllers\UserController;
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

/* Route::get('/', function () {
    return view('welcome');
}); */
Route::view('/','index')->name('index');
Auth::routes(['register'=>false]);

Route::get('/user',[UserController::class, 'index'])->name('user.index');
Route::get('/user/crear',[UserController::class, 'create'])->name('user.create');

Route::get('/General', [artistaController::class, 'index'])->name('general.index');
Route::get('/General/indexMiColeccion', [albumController::class, 'indexMiColeccion'])->name('general.indexMiColeccion');

Route::post('/Artistas/listar', [artistaController::class, 'listarAllArtistas'])->name('artistas.showArtistas');
Route::get('/Artistas/Admin', [artistaController::class, 'administrar'])->name('artistas.index');
Route::post('/Artistas/showAdmin', [artistaController::class, 'listarAdminArtistas'])->name('artistas.showAdmin');
Route::post('/Artistas/cargarPais', [artistaController::class, 'cargarPaises'])->name('artistas.cargarPais');
Route::post('/Artistas/AdminCrearArtista', [artistaController::class, 'crearArtista'])->name('artistas.crearArtista');
Route::post('/Artistas/AdminEliminarArtista', [artistaController::class, 'eliminarArtista'])->name('artistas.eliminarArtista');
Route::post('/Artistas/AdminEditarArtista', [artistaController::class, 'editarArtista'])->name('artistas.editarArtista');

Route::post('/Albums/listar', [albumController::class, 'listarAllAlbums'])->name('albums.showAlbums');
Route::get('/Albums/Admin', [albumController::class, 'administrar'])->name('albums.index');
Route::post('/Albums/showAdmin', [albumController::class, 'listarAdminAlbums'])->name('albums.showAdmin');
Route::post('/Albums/cargarArtistas', [albumController::class, 'cargarArtistas'])->name('albums.cargarArtistas');
Route::post('/Albums/AdminCrearAlbum', [albumController::class, 'crearAlbum'])->name('albums.crearAlbum');
Route::post('/Albums/AdminEliminarAlbum', [albumController::class, 'eliminarAlbum'])->name('albums.eliminarAlbum');
Route::post('/Albums/AdminEditarAlbum', [albumController::class, 'editarAlbum'])->name('albums.editarAlbum');
Route::post('/Albums/listarAlbumxArtista', [albumController::class, 'listarAlbumxArtista'])->name('albums.listarAlbumxArtista');
Route::post('/Albums/verInfoAlbum', [albumController::class, 'verInfoAlbum'])->name('albums.verInfoAlbum');
Route::post('/Albums/guardarCanciones', [albumController::class, 'guardarCanciones'])->name('albums.guardarCanciones');
Route::post('/Albums/addAlbumColeccion', [albumController::class, 'addAlbumColeccion'])->name('albums.addAlbumColeccion');
Route::post('/Albums/miColeccion', [albumController::class, 'miColeccion'])->name('albums.miColeccion');

//aca en esta sigiuente ruta estoy trabajando los routes recibo un album con el id del album , se trabaja con
//route de laravel
Route::get('/albumsColeccion/{album}/{artista}',[albumController::class, 'showAlbumColeccion'])->name('albums.show');

Route::post('/Canciones/listar', [cancionesController::class, 'listarAllCanciones'])->name('canciones.showCanciones');


Route::get('/Generos/Admin', [generoController::class, 'administrar'])->name('generos.index');
Route::post('/Generos/listar', [generoController::class, 'listarAllGeneros'])->name('generos.showGeneros');
Route::post('/Generos/showAdmin', [generoController::class, 'listarAdminGeneros'])->name('generos.showAdmin');
Route::post('/Generos/AdminCrearGenero', [generoController::class, 'crearGenero'])->name('generos.crearGenero');
Route::post('/Generos/AdminEliminarGenero', [generoController::class, 'eliminarGenero'])->name('generos.eliminarGenero');
Route::post('/Generos/AdminEditarGenero', [generoController::class, 'editarGenero'])->name('generos.editarGenero');
Route::post('/Generos/listarAlbumxGenero', [generoController::class, 'listarAlbumxGenero'])->name('generos.listarAlbumxGenero');

Route::get('/home', [artistaController::class,'index'])->name('home');

