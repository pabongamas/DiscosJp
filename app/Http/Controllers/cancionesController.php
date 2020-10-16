<?php

namespace App\Http\Controllers;

use App\Models\album;
use App\Models\artista;
use App\Models\genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cancionesController extends Controller
{
  /* Los siguientes metodos es para mostrar toda la informacion de las canciones con
      su respectivo album ,artista y genero  */
  public function listarAllCanciones()
  {
    $start = $_POST['start'];
    $limit = $_POST["length"];


    $cancionesTotal = DB::table('canciones')
      ->join('album', 'canciones.id_album', '=', 'album.id')
      ->join('album_artista', 'album_artista.album_id', '=', 'album.id')
      ->join('artistas', 'artistas.id', '=', 'album_artista.artista_id')
      ->select('canciones.*', 'album.id', 'id_genero', 'artista_id')
      ->orderBy('artistas.name')
      ->get();
    $canciones = DB::table('canciones')
      ->join('album', 'canciones.id_album', '=', 'album.id')
      ->join('album_artista', 'album_artista.album_id', '=', 'album.id')
      ->join('artistas', 'artistas.id', '=', 'album_artista.artista_id')
      ->select('canciones.*', 'album.id', 'id_genero', 'artista_id')
      ->orderBy('artistas.name')
      ->skip($start)
      ->take($limit)
      ->get();
      
    $arRegistros = array();
    foreach ($canciones as $value) {
      $obj = new \stdClass();
      $obj->numeroCancion=$value->numero_cancion;
      $obj->nameCancion=$value->name;
      $obj->duracion=$value->minutos;
      $artista = artista::find($value->artista_id);
      $obj->artista = $artista->name;
      $album = album::find($value->id_album);
      $obj->album = $album->name;
      $genero = genero::find($value->id_genero);
      $obj->genero = $genero->name;
      $obj->idCancion = $value->id;
      $obj->idAlbum = $value->id_album;
      $obj->idGenero = $value->id_genero;
      $obj->idArtista = $value->artista_id;
      
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'iTotalRecords' => count($cancionesTotal),
      'iTotalDisplayRecords' => count($cancionesTotal)
    ]);
  }
}
