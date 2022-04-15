<?php

namespace App\Http\Controllers;

use App\Mail\CodeConfirm;
use App\Mail\TestEmail;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{
    public function index()
    {
        Mail::to('ubaldo_desantiago@hotmail.com')->send(new TestEmail());
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $email = $request->input('email');
        $password = $request->input('password');
        if($user = User::where('email', $request->input('email'))->first()){
            if($user->email_verified_at == null){
                return back()->withErrors([
                    'email' => 'Tu correo todavía no ha sido verificado, verificalo por favor.',
                ]);
            }
            if(Hash::check($password, $user->password)){
                $url = URL::temporarySignedRoute('code', now()->addMinutes(10), ['email' => $user->email]);
                Mail::to($user->email)->send(new CodeConfirm($user->one_time_code));
                return redirect($url);
            }
            return back()->withErrors([
                'password' => 'Contraseña incorrecta.',
            ]);
        }
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }


    public function loginWithCode(Request $request){
        $request->validate([
            'code' => 'required|numeric'
        ]);
        $code = $request->input('code');
        error_log($code);
        if($user = User::where('email', $request->input('email'))->first()){
            error_log($user->one_time_code);
            if($user->one_time_code == $code){
                if(Auth::loginUsingId($user->id)){
                    $request->session()->regenerate();
                    $user->one_time_code = rand(100000, 999999);
                    $user->save();
                    return redirect()->intended('home');
                }
                error_log('yes');
            }
            return back()->withErrors([
                'code' => 'El código proporcionado es incorrecto.',
            ]);
        }

    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);
        $code = rand(100000, 999999);
        $email = $request->input('email');
        $user = new User();
        $user->nombre = $request->input('nombre');
        $user->email = $request->input('email');
        $user->one_time_code = $code;
        $user->password = Hash::make($request->input('password'));
        $user->id_role = $request->input('select');

        if($user->save()){
            $this->sendVerificationEmail($email);
            return redirect('/login')->with('success', 'Por favor revise su correo electrónico para verificar su cuenta');
        }
        return response()->json(['message' => 'No se pudo crear el usuario'], 500);
    }

    public function sendVerificationEmail($email="ubaldo_desantiago@hotmail.com"){
        $url = URL::temporarySignedRoute('verifyEmail', now()->addMinutes(30), ['email' => $email]);
        Mail::to($email)->send(new VerifyEmail($url));
        print_r($url);
    }

    public function verifyEmail(Request $request){
        if (! $request->hasValidSignature()) {
            return abort(401);
        }
        try{
            $user = User::where('email', $request->input('email'))->first();
            if($user->email_verified_at != null){
                return redirect('/login')->with('success', 'Tu cuenta ya está verificada');
            }
            $user->email_verified_at = now();
            $user->save();
            return redirect('/login')->with('success', 'Tu cuenta ha sido verificada');
        }catch (\Exception $e){
            return response()->json(['message' => 'El usuario no pudo ser verificado'], 500);
        }
        //return redirect('/login')->with('success', 'Your email has been verified');

    }

    public function index2()
   {
    $usuarios = User::get();
    return view('home-view')->with(compact('usuarios'));


    }

    public function delete($id)
    {
        $usuario = User::find($id);
        $usuario->delete();
        return "borrado" . $usuario;
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->nombre = $request->nombre;
        $user->email = $request->email;
        // $user->one_time_code = $code;
        $user->password = Hash::make($request->password);
        $user->id_role = $request->select;
        $user->save();
         return redirect('home-view');
        // return "chinga tu madre";

    }

    public function vistaEditar(Request $request, $id)
    {
        return view('editar');

    }



    public function amigo()
    {
        return "mensaje";
    }

}
