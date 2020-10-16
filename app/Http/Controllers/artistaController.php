<?php

namespace App\Http\Controllers;

use App\Models\Paises;
use App\Models\artista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LengthException;
use UxWeb\SweetAlert\SweetAlert;

class artistaController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  public function administrar(Request $request)
  {

    return view('artistasView.index');
  }
  public function index(Request $request)
  {

    return view('general.index');
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
    if($artistaExist!==null){
      return response()->json([
        'msg' => 'el artista '.$nombreArtista.' ya se encuentra registrado',
        'success' => false
      ]);
      
    }else{
      $artista = new artista;
      $artista->name =$nombreArtista;
      $artista->id_pais =$idPais;
      $artista->image =$imageBase64Artista;
      $artista->save();
      return response()->json([
        'msg' => '',
        'success' => true
      ]);
    } 
  }
  public function eliminarArtista(Request $request)
  {
    $idArtista=$request->idArtista;
    $artista=artista::find($idArtista);
    $artista->delete();
    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
  public function editarArtista(Request $request)
  {
    $idArtista=$request->idArtista;
    $nombreArtista=$request->nombreArtista;
    $selectPais=$request->selectPais;
    $imagenArtista=$request->imagenArtista;

    $artista=artista::find($idArtista);
    $artista->name = $nombreArtista;
    $artista->id_pais = $selectPais;
    $artista->image = $imagenArtista;
    $artista->save();
    
    return response()->json([
      'msg' => '',
      'successs' => true
    ]);
  }
}
