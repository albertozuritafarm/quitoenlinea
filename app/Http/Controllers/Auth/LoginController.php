<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Redirect;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

//    use AuthenticatesUsers;
    
     /**
     * Where to redirect users after login.
     *
     * @var string
     */
    
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(request $request){
        switch($request['company']){
            case "1":
                $BBDD = 'mysql';
                $BBDD_OTHER = 'mysql2';
                Config::set('database.default', $BBDD);
                break;
            case "2":
                $BBDD = 'mysql2';
                $BBDD_OTHER = 'mysql';
                Config::set('database.default', $BBDD);
                break;
            default:
                $BBDD = 'mysql';
                $BBDD_OTHER = 'mysql2';
                Config::set('database.default', $BBDD);
//                return redirect(url('login'))->withInput()->with('errorMsg', 'La empresa no se encuentra registrada');
        }
        //Validate if Email Exists
        $email = DB::connection($BBDD)->select('select * from users where email = ?', [$request->email]);
        if ($email) {
            if (\Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $userStatus = \Auth::User()->status_id;
                session(['db_name' => $BBDD]);
                session(['db_name_other' => $BBDD_OTHER]);
                //Validate Status
                if ($userStatus == 1) {
                    if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 6){
                        return Redirect::intended('sales/product/select');
                    }else{
                        return Redirect::intended('sales/product/select');
                    }
                } else {
                    \Auth::logout();
                    return redirect(url('login'))->withInput()->with('errorMsg', 'Su usuario se encuentra bloqueado, por favor contacte con el Administrador');
                }
            } else {
                return redirect(url('login'))->withInput()->with('errorMsg', 'La contraseña ingresada no es la correcta');
            }
        } else {
                return redirect(url('login'))->withInput()->with('errorMsg', 'El correo ingresado no se encuentra registrado');
        }
    }
    public function showLoginForm(){
        return view('auth.login');
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->flush();
        return redirect('/login');
    }
    public function recover(){
        return view('auth.recover');
    }
}
