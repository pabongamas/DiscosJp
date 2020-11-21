<?php

use App\Http\Controllers\albumController;
use App\Http\Controllers\artistaController;
use App\Http\Controllers\cancionesController;
use App\Http\Controllers\generoController;
use App\Http\Controllers\rolController;
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
Auth::routes(['register'=>true]);

Route::get('/user',[UserController::class, 'index'])->name('user.index');
Route::get('/user/crear',[UserController::class, 'create'])->name('user.create');
Route::post('/user',[UserController::class, 'store'])->name('user.store');
Route::get('/user/{user}',[UserController::class, 'show'])->name('user.show');
Route::get('/user/{user}/edit',[UserController::class, 'edit'])->name('user.edit');
Route::patch('/user/{user}',[UserController::class, 'update'])->name('user.update');
Route::delete('/user/{user}',[UserController::class, 'destroy'])->name('user.destroy');

Route::get('/rol',[rolController::class, 'index'])->name('rol.index');
Route::get('/rol/crear',[rolController::class, 'create'])->name('rol.create');
Route::post('/rol',[rolController::class, 'store'])->name('rol.store');
Route::get('/rol/{rol}',[rolController::class, 'show'])->name('rol.show');
Route::get('/rol/{rol}/edit',[rolController::class, 'edit'])->name('rol.edit');
Route::patch('/rol/{rol}',[rolController::class, 'update'])->name('rol.update');
Route::delete('/rol/{rol}',[rolController::class, 'destroy'])->name('rol.destroy');

Route::get('/General', [artistaController::class, 'index'])->name('general.index');
Route::get('/General/indexMiColeccion', [albumController::class, 'indexMiColeccion'])->name('general.indexMiColeccion');

Route::post('/Artistas/listar', [artistaController::class, 'listarAllArtistas'])->name('artistas.showArtistas');
Route::get('/Artistas/Admin', [artistaController::class, 'administrar'])->name('artistas.index');
Route::post('/Artistas/showAdmin', [artistaController::class, 'listarAdminArtistas'])->name('artistas.showAdmin');
Route::post('/Artistas/cargarPais', [artistaController::class, 'cargarPaises'])->name('artistas.cargarPais');
Route::post('/Artistas/AdminCrearArtista', [artistaController::class, 'crearArtista'])->name('artistas.crearArtista');
Route::post('/Artistas/AdminEliminarArtista', [artistaController::class, 'eliminarArtista'])->name('artistas.eliminarArtista');
Route::post('/Artistas/AdminEditarArtista', [artistaController::class, 'editarArtista'])->name('artistas.editarArtista');

/* siguientes rutas para la contribucion de artista */
Route::get('/ContribuirArtista',[artistaController::class, 'indexContribuirArtista'])->name('artistas.indexContribuir');
Route::post('/ContribuirArtista',[artistaController::class, 'contribuirArtistaStore'])->name('artista.contribuirArtistaStore');
Route::get('/ContribucionesArtista',[artistaController::class, 'showContribucion'])->name('artistas.showContribucion');
Route::patch('/ContribucionesArtista/{contribucion}',[artistaController::class, 'añadirContribucion'])->name('artistas.añadirContribucion');
Route::delete('/ContribucionesArtista/{contribucion}',[artistaController::class, 'eliminarContribucion'])->name('artistas.eliminarContribucion');

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


Route::get('/ContribuirAlbum',[albumController::class, 'indexContribuirAlbum'])->name('albums.indexContribuir');
Route::post('/ContribuirAlbum',[albumController::class, 'contribuirAlbumStore'])->name('albums.contribuirAlbumStore');
Route::get('/ContribucionesAlbum',[albumController::class, 'showContribucion'])->name('albums.showContribucion');
Route::patch('/ContribucionesAlbum/{contribucion}',[albumController::class, 'añadirContribucion'])->name('albums.añadirContribucion');
Route::delete('/ContribucionesAlbum/{contribucion}',[albumController::class, 'eliminarContribucion'])->name('albums.eliminarContribucion');

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


Route::get('/ContribuirGenero',[generoController::class, 'indexContribuirGenero'])->name('generos.indexContribuir');
Route::post('/ContribuirGenero',[generoController::class, 'contribuirGeneroStore'])->name('generos.contribuirGeneroStore');
Route::get('/ContribucionesGenero',[generoController::class, 'showContribucion'])->name('generos.showContribucion');
Route::patch('/ContribucionesGenero/{contribucion}',[generoController::class, 'añadirContribucion'])->name('generos.añadirContribucion');
Route::delete('/ContribucionesGenero/{contribucion}',[generoController::class, 'eliminarContribucion'])->name('generos.eliminarContribucion');

Route::get('/home', [artistaController::class,'index'])->name('home');

