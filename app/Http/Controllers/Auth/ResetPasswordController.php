<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */


     /* esto lo hice para indicar una vez se haya hecho el reseteo de la nueva contraseña y la haya restablecido
     el no vaya a la vista de login sino a la raiz , esto de protected se especifica porque en el archivo 
     redirectusers el pregunta si existe redirecto  y si no va al home  */
    protected $redirectTo = '/';
  /*   protected $redirectTo = RouteServiceProvider::HOME; */
}
