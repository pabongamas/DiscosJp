<?php

namespace App\Http\Controllers;

use App\Http\Requests\contribuirAlbumCreateRequest;
use App\Models\album;
use App\Models\album_artista;
use App\Models\album_user;
use App\Models\Paises;
use App\Models\artista;
use App\Models\canciones;
use App\Models\contribucion_album;
use App\Models\genero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class albumController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function administrar(Request $request)
  {
    $request->user()->authorizeRoles(['admin']);
    return view('albumsView.index');
  }
  public function indexContribuirAlbum(Request $request)
  {
    $request->user()->authorizeRoles(['user']);

    $artista = artista::all()->sortBy("name");
    $genero = genero::all()->sortBy("name");

    return view('albumsView.indexContribuirAlbum', [
      'contribucionAlbum' => new contribucion_album(),
      'genero' => $genero,
      'artista' => $artista,
    ]);
  }
  public function indexMiColeccion(Request $request)
  {
    /* $request->user()->authorizeRoles(['user']); */
    $user = $request->user()->id;
    /* $albums=album::paginate(10); */

    $artistaTotal = DB::table('album')
      ->join('album_artista', 'album_artista.album_id', '=', 'album.id')
      ->join('artistas', 'artistas.id', '=', 'album_artista.artista_id')
      ->join('album_user', 'album_user.album_id', '=', 'album.id')
      ->join('users', 'album_user.user_id', '=', 'users.id')
      ->select(
        'album.id as idAlbum',
        'album.name as nameAlbum',
        'album.image as imageAlbum',
        'artistas.id as idArtista',
        'artistas.name as nameArtista'
      )
      ->where('users.id', '=', $user)
      ->orderBy('artistas.name')
      ->orderBy('album.name')
      ->paginate(12);

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
      $obj->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";
      $arRegistros[] = $obj;
    }
    return view('general.indexMiColeccion', ['albums' => $arRegistros, 'pagination' => $artistaTotal]);
  }
  public function showAlbumColeccion(album $album, artista $artista, Request $request)
  {
    /*  $request->user()->authorizeRoles(['user']); */
    $canciones = canciones::where('id_album', $album->id)
      ->select('id', 'id_album', 'minutos', 'name', 'numero_cancion', 'numero_cancion as DT_RowId')
      ->orderBy('numero_cancion')
      ->get();
    $genero = genero::find($album->id_genero);
    $album->genero = $genero->name;
    $album->anio = date('Y', strtotime(str_replace('-', '/', $album->anio)));
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
    $album->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";


    return view('general.showAlbumColeccion', [
      'album' => $album,
      'artista' => $artista,
      'canciones' => $canciones
    ]);
  }
  public function listarAllAlbums(Request $request)
  {
    $userRol = $request->user()->hasRole('user');
    $userId = $request->user()->id;
    $artistaTotal = DB::select('select album.id as idAlbum,album.name as nameAlbum,album.image as imageAlbum
    ,artistas.id as idArtista, artistas.name as nameArtista from album 
    join album_artista on album_artista.album_id=album.id 
    join artistas on artistas.id=album_artista.artista_id order by artistas.name,album.name');
    $arRegistros = array();
    foreach ($artistaTotal as $value) {
      $obj = new \stdClass();
      $obj->id_album = $value->idAlbum;
      $obj->DT_RowId = $value->idAlbum;
      $enColeccion = DB::select('select * from album_user where user_id=' . $userId . ' and album_id=' . $value->idAlbum . '');
      if (count($enColeccion) > 0) {
        $obj->enColeccion = true;
      } else {
        $obj->enColeccion = false;
      }
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
      $obj->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'userNoAdmin' => $userRol
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
      $obj->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";

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
      $obj->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";

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
    $album->color = "rgb(" . $rPromedio . "," . $vPromedio . "," . $aPromedio . ")";
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
  public function addAlbumColeccion(Request $request)
  {
    $idAlbum = $request->idAlbum;
    $user = $request->user()->id;
    $albumUser = new album_user;
    $albumUser->user_id = $user;
    $albumUser->album_id = $idAlbum;
    $albumUser->save();
    return response()->json([
      'msg' => '',
      'success' => true
    ]);
  }

  public function miColeccion(Request $request)
  {
    $user = $request->user()->id;
    $albums = album::paginate(15);

    return view('general.indexMiColeccion', compact('albums'));
  }
  /* siguiente metodo es para crear una contribucion de un usuario */
  public function contribuirAlbumStore(contribuirAlbumCreateRequest $request)
  {
    /* dump($request->all()); */
    $image = base64_encode(file_get_contents($request->file('single-image')));
    $imgdata = base64_decode($image);
    $f = finfo_open();
    $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
    $base64 = 'data:' . $mime_type . ';base64,' . $image;
    $idUsuario = $request->user()->id;

    $contribucion = contribucion_album::create([
      'name' => request('name'),
      'image' => $base64,
      'anio' => request('anio'),
      'id_genero' => request('genero'),
      'id_artista' => request('artista'),
      'id_user' => $idUsuario
    ]);
    return redirect()->route('index')->with('status-success', 'Su contribucion ha sido creada correctamente,se le informara por correo electronico el estado de su contribución.');
  }
  /* siguiente metodo es para mostrar las contribuciones disponibles que se han hecho */
  public function showContribucion(Request $request)
  {
    $request->user()->authorizeRoles(['admin']);
    $contribuciones = DB::table('contribucion_albums')
      ->join('genero', 'contribucion_albums.id_genero', '=', 'genero.id')
      ->join('artistas', 'contribucion_albums.id_artista', '=', 'artistas.id')
      ->join('users', 'contribucion_albums.id_user', '=', 'users.id')
      ->select('contribucion_albums.id','contribucion_albums.name as name',
      'contribucion_albums.anio','contribucion_albums.image','users.fullname as usuario','genero.name as genero',
      'artistas.name as artista')
      ->orderBy('name')
      ->paginate(1);
    return view('albumsView.showContribucion', [
      'contribuciones' => $contribuciones
    ]);
  }
  /* siguiente metodo es para añadir una contribucion de un album  */
  public function añadirContribucion(contribucion_album $contribucion)
  {
    $contribucionNueva = album::create([
      'name' => $contribucion->name,
      'image' => $contribucion->image,
      'anio' => $contribucion->anio,
      'id_genero'=>$contribucion->id_genero
    ]);
    $contribucionAlbumArtista = album_artista::create([
      'artista_id' => $contribucion->id_artista,
      'album_id' => $contribucionNueva->id
    ]);
    $user =User::find($contribucion->id_user);
    $subject = "Su contribucion del album $contribucion->name fue añadida";
    $for = $user->email;
    $correoEmisor=env('MAIL_USERNAME');
    $nombreEmisor=env('APP_NAME');

    Mail::send('albumsView.emailAlbumContribucion', ['contribucion' => $contribucion], function($msj) use($subject,$for,$correoEmisor,$nombreEmisor){
        $msj->from($correoEmisor,$nombreEmisor);
        $msj->subject($subject);
        $msj->to($for);
    });
    $contribucion->delete();
    return redirect()->route('albums.showContribucion')->with('status-success', 'La contribución del album fue añadido con exito');
  }
}
