<?php

namespace App\Http\Controllers;

use App\Models\album;
use App\Models\album_artista;
use App\Models\Paises;
use App\Models\artista;
use App\Models\canciones;
use App\Models\genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class albumController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function administrar(Request $request)
  {

    return view('albumsView.index');
  }
  public function listarAllAlbums()
  {
    $artistaTotal = DB::select('select album.id as idAlbum,album.name as nameAlbum,album.image as imageAlbum
    ,artistas.id as idArtista, artistas.name as nameArtista from album 
    join album_artista on album_artista.album_id=album.id 
    join artistas on artistas.id=album_artista.artista_id order by artistas.name,album.name');
    $arRegistros = array();
    foreach ($artistaTotal as $value) {
      $obj = new \stdClass();
      $obj->id_album = $value->idAlbum;
      $obj->DT_RowId = $value->idAlbum;
      $obj->nombre = $value->nameAlbum;
      $obj->artista = $value->nameArtista;
      $obj->image = $value->imageAlbum;
      $obj->id_artista = $value->idArtista;
      /* siguiente es para obtener el color predominante la imagen  */
      $imageAlbum = explode(",", $value->imageAlbum);
      $dataImagen = base64_decode($imageAlbum[1]);
      $image = imagecreatefromstring($dataImagen);
      $rTotal = 0;
      $vTotal = 0;
      $aTotal = 0;
      $total = 0;
      for ($x = 0; $x < imagesx($image); $x++) {
        for ($y = 0; $y < imagesy($image); $y++) {
          $rgb = imagecolorat($image, $x, $y);
          $r   = ($rgb >> 16) & 0xFF;
          $v   = ($rgb >> 8) & 0xFF;
          $a   = $rgb & 0xFF;
          $rTotal += $r;
          $vTotal += $v;
          $aTotal += $a;
          $total++;
        }
      }
      $rPromedio = round($rTotal / $total);
      $vPromedio = round($vTotal / $total);
      $aPromedio = round($aTotal / $total);
      $obj->color="rgb(".$rPromedio.",".$vPromedio.",".$aPromedio.")";
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros
    ]);
  }
  /* Los siguientes metodos es para la administracion de albums */
  public function listarAdminAlbums()
  {
    $start = $_POST['start'];
    $limit = $_POST["length"];

    $artista = DB::select('select * from album join album_artista on album_artista.album_id=album.id
           order by name limit ' . $limit . ' offset ' . $start . '');
    $artistaTotal = DB::select('select * from album join album_artista on album_artista.album_id=album.id order by name');
    $arRegistros = array();
    foreach ($artista as $value) {
      $obj = new \stdClass();
      $obj->id_album = $value->id;
      $obj->DT_RowId = $value->id;
      $obj->nombre = $value->name;
      $obj->anio = $value->anio;
      $obj->id_genero = $value->id_genero;
      $artista = artista::where('id', $value->artista_id)->value('name');
      $genero = genero::where('id', $value->id_genero)->value('name');
      $obj->artista = $artista;
      $obj->artista = $genero;
      $obj->image = $value->image;
      $obj->id_artista = $value->artista_id;

       /* siguiente es para obtener el color predominante la imagen  */
       $imageAlbum = explode(",", $value->image);
       $dataImagen = base64_decode($imageAlbum[1]);
       $image = imagecreatefromstring($dataImagen);
       $rTotal = 0;
       $vTotal = 0;
       $aTotal = 0;
       $total = 0;
       for ($x = 0; $x < imagesx($image); $x++) {
         for ($y = 0; $y < imagesy($image); $y++) {
           $rgb = imagecolorat($image, $x, $y);
           $r   = ($rgb >> 16) & 0xFF;
           $v   = ($rgb >> 8) & 0xFF;
           $a   = $rgb & 0xFF;
           $rTotal += $r;
           $vTotal += $v;
           $aTotal += $a;
           $total++;
         }
       }
       $rPromedio = round($rTotal / $total);
       $vPromedio = round($vTotal / $total);
       $aPromedio = round($aTotal / $total);
       $obj->color="rgb(".$rPromedio.",".$vPromedio.",".$aPromedio.")";

      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'iTotalRecords' => count($artistaTotal),
      'iTotalDisplayRecords' => count($artistaTotal)
    ]);
    /*   return \Response::json($usuario); */
  }
  public function cargarArtistas()
  {
    $artista = artista::all()->sortBy("name");
    $genero = genero::all()->sortBy("name");
    $arRegistros = array();
    $arRegistrosGenero = array();
    foreach ($artista as $value) {
      $obj = new \stdClass();
      $obj->id_artista = $value->id;
      $obj->nombre = $value->name;
      $arRegistros[] = $obj;
    }
    foreach ($genero as $value) {
      $obj = new \stdClass();
      $obj->id_genero = $value->id;
      $obj->nombre = $value->name;
      $arRegistrosGenero[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'genero' => $arRegistrosGenero
    ]);
  }
  public function crearAlbum(Request $request)
  {
    $nombreAlbum = $request->nombreAlbum;
    $idArtista = $request->idArtista;
    $imageBase64Album = $request->imageBase64Album;
    $anio = $request->anio;
    $genero = $request->idGenero;

    $albumExist = album::where('name', $nombreAlbum)->first();
    if ($albumExist !== null) {
      return response()->json([
        'msg' => 'el album ' . $nombreAlbum . ' ya se encuentra registrado',
        'success' => false
      ]);
    } else {
      $album = new album;
      $album->name = $nombreAlbum;
      $album->image = $imageBase64Album;
      $album->anio = $anio;
      $album->id_genero = $genero;
      $album->save();
      $albumArtista = new album_artista;
      $albumArtista->artista_id = $idArtista;
      $albumArtista->album_id = $album->id;
      $albumArtista->save();
      return response()->json([
        'msg' => '',
        'success' => true
      ]);
    }
  }
  public function eliminarAlbum(Request $request)
  {

    $idAlbum = $request->idAlbum;
    $idArtista = $request->idArtista;
    DB::delete('delete from album_artista where artista_id=' . $idArtista . ' and album_id=' . $idAlbum . '');
    $album = album::find($idAlbum);
    $album->delete();
    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
  public function editarAlbum(Request $request)
  {
    $idAlbum = $request->idAlbum;
    $nombreArtista = $request->nombreAlbum;
    $selectArtista = $request->selectArtista;
    $imagenAlbum = $request->imagenAlbum;
    $idArtistaOld = $request->idArtista;
    $anio = $request->anio;
    $selectGenero = $request->selectGenero;

    $album = album::find($idAlbum);
    $album->name = $nombreArtista;
    $album->image = $imagenAlbum;
    $album->anio = $anio;
    $album->id_genero = $selectGenero;
    $album->save();

    $editActividad = DB::table('album_artista')
      ->where(['artista_id' => $idArtistaOld, 'album_id' => $idAlbum])
      ->update(['artista_id' => $selectArtista]);

    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
  public function listarAlbumxArtista(Request $request)
  {
    $idArtista = $request->idArtista;
    $artista = artista::where('id', $idArtista)->value('name');
    $albums = DB::table('album')
      ->join('album_artista', 'album_artista.album_id', '=', 'album.id')
      ->join('artistas', 'artistas.id', '=', 'album_artista.artista_id')
      ->select('album.id as idAlbum', 'album.name as nameAlbum', 'album.image as imageAlbum', 'artistas.id as idArtista', 'album.anio as anio', 'album.id_genero as id_genero')
      ->where('artistas.id', '=', $idArtista)
      ->orderBy('album.name')
      ->get();
    $arRegistros = array();
    foreach ($albums as $value) {
      $obj = new \stdClass();
      $obj->idAlbum = $value->idAlbum;
      $obj->nameAlbum = $value->nameAlbum;
      $obj->imageAlbum = $value->imageAlbum;
      $obj->idArtista = $value->idArtista;
      $obj->anio = date('Y', strtotime(str_replace('-', '/', $value->anio)));
      $obj->id_genero = $value->id_genero;
      $genero = genero::find($value->id_genero);
      $obj->genero = $genero->name;
      /*armo el objeto para cargar las canciones correspondientes a un album  */
      $canciones = DB::table('canciones')
        ->select('*')
        ->where('canciones.id_album', '=', $value->idAlbum)
        ->orderBy('numero_cancion')
        ->get();
      $obj->canciones = $canciones;
      /* siguiente es para obtener el color predominante la imagen  */
      $imageAlbum = explode(",", $value->imageAlbum);
      $dataImagen = base64_decode($imageAlbum[1]);
      $image = imagecreatefromstring($dataImagen);
      $rTotal = 0;
      $vTotal = 0;
      $aTotal = 0;
      $total = 0;
      for ($x = 0; $x < imagesx($image); $x++) {
        for ($y = 0; $y < imagesy($image); $y++) {
          $rgb = imagecolorat($image, $x, $y);
          $r   = ($rgb >> 16) & 0xFF;
          $v   = ($rgb >> 8) & 0xFF;
          $a   = $rgb & 0xFF;
          $rTotal += $r;
          $vTotal += $v;
          $aTotal += $a;
          $total++;
        }
      }
      $rPromedio = round($rTotal / $total);
      $vPromedio = round($vTotal / $total);
      $aPromedio = round($aTotal / $total);
      $obj->color="rgb(".$rPromedio.",".$vPromedio.",".$aPromedio.")";

      $arRegistros[] = $obj;
    }

    return response()->json([
      'msg' => '',
      'successs' => true,
      'artista' => $artista,
      'albums' => $arRegistros,
      'totalAlbums' => count($albums)
    ]);
  }
  /*el siguiente metodo es para cargar la informacion de un album */
  public function verInfoAlbum(Request $request)
  {
    $idAlbum = $request->idAlbum;
    $idArtista = $request->idArtista;
    $album = album::find($idAlbum);
    $genero = genero::find($album->id_genero);
    $artista = artista::find($idArtista);
    $album->genero = $genero->name;
    $album->artista = $artista->name;
    $album->anio = date('Y', strtotime(str_replace('-', '/', $album->anio)));
    $canciones = canciones::where('id_album', $idAlbum)
      ->select('id', 'id_album', 'minutos', 'name', 'numero_cancion', 'numero_cancion as DT_RowId')
      ->orderBy('numero_cancion')
      ->get();

      /* siguiente es para obtener el color predominante la imagen  */
      $imageAlbum = explode(",", $album->image);
      $dataImagen = base64_decode($imageAlbum[1]);
      $image = imagecreatefromstring($dataImagen);
      $rTotal = 0;
      $vTotal = 0;
      $aTotal = 0;
      $total = 0;
      for ($x = 0; $x < imagesx($image); $x++) {
        for ($y = 0; $y < imagesy($image); $y++) {
          $rgb = imagecolorat($image, $x, $y);
          $r   = ($rgb >> 16) & 0xFF;
          $v   = ($rgb >> 8) & 0xFF;
          $a   = $rgb & 0xFF;
          $rTotal += $r;
          $vTotal += $v;
          $aTotal += $a;
          $total++;
        }
      }
      $rPromedio = round($rTotal / $total);
      $vPromedio = round($vTotal / $total);
      $aPromedio = round($aTotal / $total);
      $album->color="rgb(".$rPromedio.",".$vPromedio.",".$aPromedio.")";
      /* $album->artista = $artista->name; */

    return response()->json([
      'msg' => '',
      'successs' => true,
      'album' => $album,
      'canciones' => $canciones
    ]);
  }
  public function guardarCanciones(Request $request)
  {
    $idAlbumActual = $request->idAlbumActual;
    $canciones = json_decode($request->canciones);
    foreach ($canciones as $value) {
      if (isset($value->id)) {
        $canciones = canciones::find($value->id);
        $canciones->numero_cancion = $value->numero_cancion;
        $canciones->name = $value->name;
        $canciones->minutos = $value->minutos;
        $canciones->save();
      } else {
        $cancion = new canciones;
        $cancion->numero_cancion = $value->numero_cancion;
        $cancion->name = $value->name;
        $cancion->minutos = $value->minutos;
        $cancion->id_album = $idAlbumActual;
        $cancion->save();
      }
    }
    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
}
