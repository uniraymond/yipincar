<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use GuzzleHttp\Psr7\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth as Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

//    protected $guard = 'admin';

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'captcha' => 'required|captcha'
        ], $this->messages());
    }

    public function messages()
    {
        return [
            'name.required' => '名字是必填的',
            'name.max' => '名字太长了',
            'email.required'  => '电子邮件是必填的',
            'email.email'  => '电子邮件格式不正确',
            'email.max'  => '电子邮件太长了',
            'email.unique'  => '电子邮件已经被注册过了',
            'password.required'  => '密码是必填的,最少6个字符',
            'password.min'  => '密码最少6个字符',
            'password.confirmed'  => '两个密码不一样',
            'password_confirmation.required'  => '确定密码是必填的,最少6个字符',
            'password_confirmation.min'  => '确定密码最少是6个字符',
            'password_confirmation.same'  => '两个密码不一样',
            'captcha.required' => '请输入验证码',
            'captcha.captcha' => '输入的验证码错误',
        ];
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $new_user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'secret' => bcrypt($data['password']),
            'password' => md5($data['password']),
        ]);
        $new_user->roles()->attach(Role::where('name', 'user')->first());
        return $new_user;
    }

    public function postSignUp(Request $request)
    {
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->secret = bcrypt($request['password']);
        $user->password = md5($request['password']);
        $user->save();
        $user->roles()->attach(Role::where('name', $request['role'])->first());
        Auth::login($user);
        return redirect()->route('welcome');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if (property_exists($this, 'registerView')) {
            return view($this->registerView);
        }

        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        return redirect($this->redirectPath());
    }

    public function getLogout(){
       if (Auth::user()->hasAnyRole(['auth_editor'])) {
           Auth::logout();
           return redirect()->route('authlogin');
       } else {
           Auth::logout();
           return redirect()->route('login');
       }
    }
    
    public function authregister()
    {
        return view('authusers.authregister');
    }

    public function authforgetpw()
    {
        return view('auth.authforgetpw');
    }

    public function authlogin()
    {
        return view('auth.authlogin');
    }
}
