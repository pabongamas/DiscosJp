<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolCreateRequest;
use App\Http\Requests\UpdateRolRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class rolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $roles = DB::table('roles')
        ->select('roles.*')
        ->orderBy('roles.name')
        ->paginate(10);
        return view('rol.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rol.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolCreateRequest $request)
    {
        $rol = Role::create([
            'name' => request('name'),
            'description' => request('description')
        ]);
        return redirect()->route('rol.index')->with('status-success', 'El rol ' . $rol->name . ' fue creado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $rol)
    {
        return view('rol.show', [
            'rol' => $rol
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $rol)
    {
        return view('rol.edit', [
            'rol' => $rol
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolRequest $request, Role $rol)
    {
        $role = Role::find($rol->id);
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();
        return redirect()->route('rol.index')->with('status', 'El rol ' . $role->name . ' se ha actualizado Correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $rol)
    {
        $idRol = $rol->id;
        $tablas = array('role_user');
        $cont = 0;
        foreach ($tablas as $tabla) {
            if ($tabla == "role_user") {
                $idCampo = 'role_id';
            }
            $rolUtilizado = DB::select('select * from ' . $tabla . ' where ' . $idCampo . '=' . $idRol . ' limit 1');
            if (count($rolUtilizado) > 0) {
                $cont++;
            } else {
            }
        }
        if ($cont == 0) {
            $rol->delete();
            return redirect()->route('rol.index')->with('status-success', 'El rol fue eliminado con exito');
        } else {
            return redirect()->route('rol.index')->with('status-error', 'El rol ' . $rol->name . ' esta siendo utilizado en otros modulos,no se puede eliminar');
        }
    }
}
