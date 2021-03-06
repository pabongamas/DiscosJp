<?php

namespace App\Http\Controllers;

use App\Http\Requests\contribuirGeneroCreateRequest;
use App\Models\artista;
use App\Models\contribucion_genero;
use App\Models\genero;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class generoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function administrar(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('generoView.index');
    }
    /* siguiente metodo es para el redireccionamiento para contribuir un genero */
    public function indexContribuirGenero(Request $request)
    {
        $request->user()->authorizeRoles(['user']);
        return view('generoView.indexContribuirGenero', [
            'contribucionGenero' => new contribucion_genero(),
        ]);
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
    public function listarAlbumxGenero(Request $request)
    {
        $idGenero = $request->idGenero;
        $generoName = genero::where('id', $idGenero)->value('name');
        $contCanciones = 0;
        $albums = DB::table('album')
            ->join('album_artista', 'album_artista.album_id', '=', 'album.id')
            ->join('artistas', 'artistas.id', '=', 'album_artista.artista_id')
            ->join('genero', 'album.id_genero', '=', 'genero.id')
            ->select('album.id as idAlbum', 'album.name as nameAlbum', 'album.image as imageAlbum', 'artistas.id as idArtista', 'album.anio as anio', 'album.id_genero as id_genero')
            ->where('id_genero', '=', $idGenero)
            ->orderBy('album.name')
            ->get();
        $arRegistros = array();
        foreach ($albums as $value) {
            $obj = new \stdClass();
            $obj->idAlbum = $value->idAlbum;
            $obj->nameAlbum = $value->nameAlbum;
            $obj->imageAlbum = $value->imageAlbum;
            $obj->idArtista = $value->idArtista;
            $Artista = artista::find($value->idArtista);
            $obj->artista = $Artista->name;
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
            $contCanciones = $contCanciones + count($canciones);

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
            'genero' => $generoName,
            'albums' => $arRegistros,
            'totalAlbums' => count($albums),
            'totalCanciones' => $contCanciones
        ]);
    }
    /* siguiente metodo recibe la contribucion de genero que haga un usuario */
    public function contribuirGeneroStore(contribuirGeneroCreateRequest $request)
    {
        $idUsuario = $request->user()->id;

        $contribucion = contribucion_genero::create([
            'name' => request('name'),
            'id_user' => $idUsuario
        ]);
        return redirect()->route('index')->with('status-success', 'Su contribucion ha sido creada correctamente,se le informara por correo electronico el estado de su contribución.');
    }
    /* siguiente metodo es para mostrar las contribuciones disponibles que se han hecho */
    public function showContribucion(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $contribuciones = DB::table('contribucion_generos')
            ->join('users', 'contribucion_generos.id_user', '=', 'users.id')
            ->select(
                'contribucion_generos.id',
                'contribucion_generos.name as name',
                'users.fullname as usuario'
            )
            ->orderBy('name')
            ->paginate(10);
        return view('generoView.showContribucion', [
            'contribuciones' => $contribuciones
        ]);
    }
    /* siguiente metodo es para añadir una contribucion de un album  */
  public function añadirContribucion(contribucion_genero $contribucion)
  {
    $contribucionNueva = genero::create([
      'name' => $contribucion->name
    ]);

    $user =User::find($contribucion->id_user);
    $subject = "Su contribucion del genero $contribucion->name fue añadida";
    $for = $user->email;
    $correoEmisor=env('MAIL_USERNAME');
    $nombreEmisor=env('APP_NAME');

    Mail::send('generoView.emailGeneroContribucion', ['contribucion' => $contribucion], function($msj) use($subject,$for,$correoEmisor,$nombreEmisor){
        $msj->from($correoEmisor,$nombreEmisor);
        $msj->subject($subject);
        $msj->to($for);
    });
    $contribucion->delete();
    return redirect()->route('generos.showContribucion')->with('status-success', 'La contribución del genero fue añadido con exito');
  }
  public function eliminarContribucion(contribucion_genero $contribucion)
  {
    $contribucion->delete();
    return redirect()->route('generos.showContribucion')->with('status-success', 'La contribución del genero fue eliminada correctamente.');
  }
}
