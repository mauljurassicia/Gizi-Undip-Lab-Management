<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek User di db sispin

        $userLogin = \App\user::where('email', $request->email)->first();
        if(empty($userLogin)){
            return redirect()->back()->withErrors(['message' => 'Email dan Password tidak sesuai'])->withInput($request->only('email', 'remember'));
        }

        // Kalau ada cek tenancy id 
        if($userLogin->tenancy_id > 0){
            // kalo ada create db akses  cek user di tabel user db tenancy
            // masukin auth nya dan redirect ke url tenancy dashboard 

            $tenancy = \App\Models\Tenancy::find($userLogin->tenancy_id);
            if(empty($tenancy)){
                return redirect()->back()->withErrors(['message' => 'Invalid User Login, silahkan hubungi Administrator Koperasi anda untuk memperbaiki data login anda'])->withInput($request->only('email', 'remember'));
            }
            else{
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], @$request->remember)) {
                    $account = Auth::user();
                    $request->session()->regenerate();
                    session(['url' => '']); // tenancy/
                    return redirect()->intended('dashboard');

                    // $newCon = 'tenant';
                    // config(['database.connections.'.$newCon => [
                    //     'driver' => 'mysql',
                    //     'host' => env('DB_HOST'),
                    //     'port' => env('DB_PORT'),
                    //     'database' => $tenancy->db,
                    //     'username' => env('TENANT_DB_USERNAME'),
                    //     'password' => env('TENANT_DB_PASSWORD')
                    // ]]);
                    // DB::setDefaultConnection($newCon);
                    
                    // session(['url' => $tenancy->url]);
                    // return redirect($tenancy->url . '/dashboard');
                }  
                else{
                    dd($request->email, $request->password);
                    return redirect()->back()->withErrors(['message' => 'Email dan Password tidak sesuai'])->withInput($request->only('email', 'remember'));
                }
            }
        }
        else{
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], @$request->remember)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }  
        }        
    }
}
