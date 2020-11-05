<?php

namespace App\Http\Controllers;

use App\Http\Requests\contribuirArtistaCreateRequest;
use App\Models\Paises;
use App\Models\artista;
use App\Models\contribucion_artista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use LengthException;
use UxWeb\SweetAlert\SweetAlert;

use function GuzzleHttp\Promise\all;

class artistaController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function administrar(Request $request)
  {
    $request->user()->authorizeRoles(['admin']);
    return view('artistasView.index');
  }
  public function index(Request $request)
  {
    return view('general.index');
  }
  /* siguiente metodo es para redirigir a la vista de contribuir artistas  */
  public function indexContribuirArtista()
  {
    /*  return view('artistasView.indexContribuirArtista'); */
    $pais = Paises::all()->sortBy("name");

    return view('artistasView.indexContribuirArtista', [
      'contribucionArtista' => new contribucion_artista(),
      'pais' => $pais
    ]);
  }
  /* siguiente metodo es para mostrar las contribuciones disponibles que se han hecho */
  public function showContribucion(Request $request)
  {
    $request->user()->authorizeRoles(['admin']);
    $contribuciones = DB::table('contribucion_artista')
      ->join('paises', 'contribucion_artista.id_pais', '=', 'paises.id')
      ->join('users', 'contribucion_artista.id_user', '=', 'users.id')
      ->select('contribucion_artista.id', 'contribucion_artista.name', 'contribucion_artista.image', 'paises.name as nombrePais', 'users.fullname as usuario')
      ->orderBy('name')
      ->paginate(1);
    return view('artistasView.showContribucion', [
      'contribuciones' => $contribuciones
    ]);
  }
  public function listarAllArtistas()
  {
    $start = $_POST['start'];
    $limit = $_POST["length"];

    $artista = DB::select('select * from artistas
          order by name limit ' . $limit . ' offset ' . $start . '');
    $artistaTotal = DB::select('select * from artistas order by name');
    $arRegistros = array();
    foreach ($artista as $value) {
      $obj = new \stdClass();
      $obj->id_artista = $value->id;
      $obj->DT_RowId = $value->id;
      $obj->nombre = $value->name;
      $obj->image = $value->image;
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'iTotalRecords' => count($artistaTotal),
      'iTotalDisplayRecords' => count($artistaTotal)
    ]);
    /*   return \Response::json($usuario); */
  }
  /* Los siguientes metodos es para la administracion de artistas */
  public function listarAdminArtistas()
  {
    $start = $_POST['start'];
    $limit = $_POST["length"];

    $artista = DB::select('select * from artistas
          order by name limit ' . $limit . ' offset ' . $start . '');
    $artistaTotal = DB::select('select * from artistas order by name');
    $arRegistros = array();
    foreach ($artista as $value) {
      $obj = new \stdClass();
      $obj->id_artista = $value->id;
      $obj->DT_RowId = $value->id;
      $obj->nombre = $value->name;
      $pais = Paises::where('id', $value->id_pais)->value('name');
      $obj->pais = $pais;
      $obj->id_pais = $value->id_pais;
      $obj->image = $value->image;
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros,
      'iTotalRecords' => count($artistaTotal),
      'iTotalDisplayRecords' => count($artistaTotal)
    ]);
    /*   return \Response::json($usuario); */
  }
  public function cargarPaises()
  {
    $pais = Paises::all()->sortBy("name");
    $arRegistros = array();
    foreach ($pais as $value) {
      $obj = new \stdClass();
      $obj->id_pais = $value->id;
      $obj->nombre = $value->name;
      $arRegistros[] = $obj;
    }
    return response()->json([
      'data' => $arRegistros
    ]);
  }

  public function crearArtista(Request $request)
  {
    $data = $request->all();
    $nombreArtista = $data["nombreArtista"];
    $idPais = $data["idPais"];
    $imageBase64Artista = $data["imageBase64Artista"];

    $artistaExist = artista::where('name', $nombreArtista)->first();
    if ($artistaExist !== null) {
      return response()->json([
        'msg' => 'el artista ' . $nombreArtista . ' ya se encuentra registrado',
        'success' => false
      ]);
    } else {
      $artista = new artista;
      $artista->name = $nombreArtista;
      $artista->id_pais = $idPais;
      $artista->image = $imageBase64Artista;
      $artista->save();
      return response()->json([
        'msg' => '',
        'success' => true
      ]);
    }
  }
  public function eliminarArtista(Request $request)
  {
    $idArtista = $request->idArtista;
    $artista = artista::find($idArtista);
    $artista->delete();
    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
  public function editarArtista(Request $request)
  {
    $idArtista = $request->idArtista;
    $nombreArtista = $request->nombreArtista;
    $selectPais = $request->selectPais;
    $imagenArtista = $request->imagenArtista;

    $artista = artista::find($idArtista);
    $artista->name = $nombreArtista;
    $artista->id_pais = $selectPais;
    $artista->image = $imagenArtista;
    $artista->save();

    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
  /* siguientes metodo es la contribucion de artistas  */
  public function contribuirArtistaStore(contribuirArtistaCreateRequest $request)
  {
    $image = base64_encode(file_get_contents($request->file('single-image')));
    $imgdata = base64_decode($image);
    $f = finfo_open();
    $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
    $base64 = 'data:' . $mime_type . ';base64,' . $image;
    $idUsuario=$request->user()->id;

    $contribucion = contribucion_artista::create([
      'name' => request('name'),
      'image' => $base64,
      'id_pais' => request('pais'),
      'id_user'=>$idUsuario
    ]);
    return redirect()->route('index')->with('status-success', 'Su contribucion ha sido creada correctamente,se le informara por correo electronico el estado de su contribución.');
  }
  public function añadirContribucion(contribucion_artista $contribucion)
  {
    $contribucionNueva = artista::create([
      'name' => $contribucion->name,
      'image' => $contribucion->image,
      'id_pais' => $contribucion->id_pais
    ]);
    $contribucion->delete();
    return redirect()->route('artistas.showContribucion')->with('status-success', 'La contribucion del artista fue añadido con exito');
  }
}
