<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\role_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        $users = DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.*', 'roles.id as idRol', 'roles.name as rolName', 'roles.description as rolDescription')
            ->orderBy('users.name')
            ->paginate(10);
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create', [
            'user' => new User,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {

        $usuario = User::create([
            'name' => request('name'),
            'fullname' => request('fullname'),
            'password' => bcrypt(request('password')),
            'email' => request('email'),
            'birthdate' => request('birthdate'),
            'gender' => request('gender')
        ]);
        $idRol = (request('rol'));
        if ($idRol !== "0") {
            $idUsuario = $usuario->id;
            $role_user = new role_user();
            $role_user->user_id = $idUsuario;
            $role_user->role_id = $idRol;
            $role_user->save();
        }

        return redirect()->route('user.index')->with('status-success', 'El usuario ' . $usuario->name . ' fue creado con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRol = DB::table('role_user')
            ->where('user_id', '=', $user->id)
            ->get();
        return view('user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRol' => $userRol
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        /* var_dump($request->password);
        exit;
        $user->update($request->validated()); */
        $user = User::find($user->id);
        $user->name = $request->name;
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->birthdate = $request->birthdate;
        $user->gender = $request->gender;
        $user->password =bcrypt($request->password);
        $user->save();
        if ($request->rol == "0") {
            $userRol = DB::table('role_user')
                ->where('user_id', '=', $user->id)
                ->get();

            if (count($userRol) > 0) {
                DB::table('role_user')->where('user_id', '=', $user->id)->delete();
            }
        } else {
            $userRol = DB::table('role_user')
                ->where('user_id', '=', $user->id)
                ->get();
            if (count($userRol) > 0) {
                $Rol = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->update(['role_id' => $request->rol]);
            } else {
                DB::table('role_user')->insert([
                    ['user_id' => $user->id, 'role_id' => $request->rol]
                ]);
            }
        }
        return redirect()->route('user.index')->with('status', 'El usuario ' . $user->name . ' se ha actualizado Correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $idUsuario = $user->id;
        $tablas = array('role_user', 'album_user');
        $cont = 0;
        foreach ($tablas as $tabla) {
            if ($tabla == "role_user") {
                $idCampo = 'user_id';
            } else if ("album_user") {
                $idCampo = 'user_id';
            }
            $userUtilizado = DB::select('select * from ' . $tabla . ' where ' . $idCampo . '=' . $idUsuario . ' limit 1');
            if (count($userUtilizado) > 0) {
                $cont++;
            } else {
            }
        }
        if ($cont == 0) {
            $user->delete();
            return redirect()->route('user.index')->with('status-success', 'El usuario fue eliminado con exito');
        } else {

            return redirect()->route('user.index')->with('status-error', 'El usuario ' . $user->name . ' esta siendo utilizado en otros modulos,no se puede eliminar');
        }
    }
}
