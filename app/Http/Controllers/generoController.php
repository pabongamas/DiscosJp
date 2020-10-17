<?php

namespace App\Http\Controllers;

use App\Models\genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class generoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function administrar(Request $request)
    {

        return view('generoView.index');
    }
    /* metodo para listar todos los  generos en la vista principal  */
    public function listarAllGeneros(Request $request)
    {
        $start = $request->start;
        $limit = $request->length;
        $genero = DB::select('select * from genero 
        order by name limit ' . $limit . ' offset ' . $start . '');
        $generoTotal = DB::select('select * from genero  order by name');
        $arRegistros = array();
        foreach ($genero as $value) {
            $obj = new \stdClass();
            $obj->id_genero = $value->id;
            $obj->DT_RowId = $value->id;
            $obj->nombre = $value->name;
            $arRegistros[] = $obj;
        }
        return response()->json([
            'data' => $arRegistros,
            'iTotalRecords' => count($generoTotal),
            'iTotalDisplayRecords' => count($generoTotal)
        ]);
    }

    /* Los siguientes metodos es para la administracion de generos */
    public function listarAdminGeneros()
    {
        $start = $_POST['start'];
        $limit = $_POST["length"];

        $genero = DB::select('select * from genero 
          order by name limit ' . $limit . ' offset ' . $start . '');
        $generoTotal = DB::select('select * from genero  order by name');
        $arRegistros = array();
        foreach ($genero as $value) {
            $obj = new \stdClass();
            $obj->id_genero = $value->id;
            $obj->DT_RowId = $value->id;
            $obj->nombre = $value->name;
            $arRegistros[] = $obj;
        }
        return response()->json([
            'data' => $arRegistros,
            'iTotalRecords' => count($generoTotal),
            'iTotalDisplayRecords' => count($generoTotal)
        ]);
        /*   return \Response::json($usuario); */
    }
    public function crearGenero(Request $request)
    {
        $nombreGenero = $request->nombreGenero;
        $generoExist = genero::where('name', $nombreGenero)->first();
        if ($generoExist !== null) {
            return response()->json([
                'msg' => 'el genero ' . $nombreGenero . ' ya se encuentra registrado',
                'success' => false
            ]);
        } else {
            $genero = new genero;
            $genero->name = $nombreGenero;
            $genero->save();
            return response()->json([
                'msg' => '',
                'success' => true
            ]);
        }
    }
    public function eliminarGenero(Request $request)
    {

        $idGenero = $request->idGenero;
        $tablas = array('album');
        $cont = 0;
        foreach ($tablas as $tabla) {
            if ($tabla == "album") {
                $idCampo = 'id_genero';
            }
            /*   else if ($tabla == "usuario_rol") {
          $idCampo = 'id_usuario';
        } */
            $generoUtilizado = DB::select('select * from ' . $tabla . ' where ' . $idCampo . '=' . $idGenero . ' limit 1');
            if (count($generoUtilizado) > 0) {
                $cont++;
            } else {
            }
        }
        if ($cont == 0) {
            $esUsado = false;
            $genero = genero::find($idGenero);
            $genero->delete();
        } else {
            $esUsado = true;
        }
        return response()->json([
            'msg' => '',
            'successs' => true,
            'usado' => $esUsado
        ]);
    }
    public function editarGenero(Request $request)
    {
        $idGenero = $request->idGenero;
        $nombreGenero = $request->nombreGenero;
        $album = genero::find($idGenero);
        $album->name = $nombreGenero;
        $album->save();


        return response()->json([
            'msg' => '',
            'successs' => true
        ]);
    }
}
