<?php

namespace App\Http\Controllers;

use App\Article;
use App\Role;
use App\UserStatus;
use Illuminate\Support\Facades\DB as DB;
use App\UserRoles;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Profile;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Support\Facades\Auth as Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = $request->user();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            $roles = Role::all();
            $users = User::paginate(10);
            return view('users/index', ['users'=>$users, 'usergroups'=>$roles]);
        }

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $auth = $request->user();
        $statuses = UserStatus::all();
        $roles = Role::where('name','<>', 'super_admin')->get();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            return view('users/create', ['roles'=>$roles, 'usergroups'=>$roles, 'statuses'=>$statuses]);
        }
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $data['name'] = $request['name'];
//        $data['email'] = $request['email'];
//        $data['password'] = $request['password'];
//        $data['roles'] = $request['roles'];
        $valideType = 'new';
        $validator = $this->validator($request->all(), $valideType);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        if($request['status_id'] == 4) {
            $banned = 1;
        } else {
            $banned = 0;
        }

        $roleIds = $request['roles'];
        $new_user = new User();
        $new_user->name = $request['name'];
        $new_user->email = $request['email'];
        $new_user->password = bcrypt($request['password']);
        $new_user->status_id = $request['status_id'];
        $new_user->pre_status_id = $request['status_id'];
        if($request['status_id'] == 4) {
            $new_user->banned = 1;
        }
        $new_user->save();

        foreach($roleIds as $roleId) {
            $new_user->roles()->attach($roleId);
        }

        $request->session()->flash('status', '成功创建用户: '. $new_user->name);
        return redirect('admin/user');
    }

    /**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function show(Request $request, $id)
    {
        $auth = $request->user();
        $user = User::findorFail($id);
        $articles = User::find($id)->articles()->orderBy('created_at', 'desc')->get();

        return view('users/show', ['user'=>$user, 'articles'=>$articles]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $id)
    {
        $auth = $request->user();
        $user = User::findorFail($auth->id);
        $articles = User::find($auth->id)->articles()->orderBy('created_at', 'desc')->get();

        return view('users/show', ['user'=>$user, 'articles'=>$articles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $auth = $request->user();
        $statuses = UserStatus::all();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            $roles = Role::where('name','<>', 'super_admin')->get();
            $user = User::findorFail($id);
            return view('users/edit', ['user'=> $user, 'roles'=>$roles, 'usergroups'=>$roles, 'statuses'=>$statuses]);
        }
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emailChanges = true;
        $checkemail = User::where('email', $request['email'])->first();
        if (isset($checkemail) && $id == $checkemail->id)  {
            $emailChanges = false;
        }
        $valideType = 'checkemail';
        $validator = $this->validator($request->all(), $valideType);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $auth = $request->user();
        $roleIds = $request['roles'];
        $roles = Role::all();
        $currentUser = User::find($id);
        $currentUserRoles = $currentUser->userrole;
        $currentRoleIds = array();

        foreach ($currentUserRoles as $currUserRole) {
            $currentRoleIds[] = $currUserRole->role_id;
        }

        foreach ($roles as $role) {
            $allRoleIds[] = $role->id;
        }
        foreach($roleIds as $roleId) {
            if (in_array($roleId, $allRoleIds) &&
                !(in_array($roleId, $currentRoleIds))) {
                $currentUser->roles()->attach($roleId);
            }
        }

        foreach ($currentRoleIds as $cid) {
            if (!in_array($cid, $roleIds)) {
                $currentUser->roles()->detach($cid);
            }
        }

            $currentUser->name = $request['name'];

            $emailUser =  $currentUser->where('email',$request['email'])->get();
            if ($id !=  $checkemail->id)  {
                $currentUser->email = $request['email'];
            }

            if ($request['password']) {
                $currentUser->password = bcrypt($request['password']);
            }

            if ($request['status_id']) {
                $currentUser->pre_status_id = $currentUser->status_id;
                $currentUser->status_id = $request['status_id'];
                if($request['status_id'] == 4) {
                    $currentUser->banned = 1;
                } else {
                    $currentUser->banned = 0;
                }
            }
            $currentUser->save();

        $request->session()->flash('status', '用户: '. $currentUser->name .'升级资料成功!');
        return redirect('admin/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $userName = $user->name;
        $user->delete();

        $request->session()->flash('status', '用户: '. $userName .' 被成功删除了.');
        return redirect('admin/user');
    }


    public function banned(Request $request, $id)
    {
        $auth = $request->user();

        $user = User::findorFail($id);
        if ($user) {
            $status_id = $user->status_id;
            $user->banned = 1;
            $user->status_id = 4;
            $user->pre_status_id = $status_id;
            $user->save();
        }
        $request->session()->flash('status', '用户: '. $user->name .' 已被屏蔽.');
        return redirect('admin/user');
    }

    public function active(Request $request, $id)
    {
        $auth = $request->user();

        $user = User::findorFail($id);
        if ($user) {
            $status_id = $user->status_id;
            $user->banned = 0;
            $user->status_id = $user->pre_status_id;
            $user->save();
            $user->pre_status_id = $status_id;
            $user->save();
        }
        $request->session()->flash('status', '被屏蔽的用户: '. $user->name .' 已被恢复.');
        return redirect('admin/user');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data,$valideType)
    {
        switch($valideType) {
            case 'new':
                return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                    'roles' => 'required',
                    'captcha' => 'required|captcha'
                ], $this->messages($valideType));
                break;
            case 'checkemail':
                return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'confirmed',
                    'roles' => 'required',
                ], $this->messages($valideType));
                break;
            case 'authuser':
                return Validator::make($data, [
                    'phone' => 'required|max:14|min:11',
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                    'captcha' => 'required|captcha',
                    'confirmterm' => 'required'
                ], $this->messages($valideType));
                break;
            case 'login':
                return Validator::make($data, [
                    'phone' => 'required|max:14|min:11',
                    'password' => 'required',
                    'captcha' => 'required|captcha',
                ], $this->messages($valideType));
                break;
            default:
                return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'password' => 'confirmed',
                    'roles' => 'required'
                ], $this->messages($valideType));
                break;
        }
    }

    public function messages($valideType)
    {
        switch($valideType) {
            case 'new':
                return [
                    'name.required' => '名字是必填的',
                    'name.max' => '名字太长了',
                    'email.required'  => '电子邮件是必填的',
                    'email.email'  => '电子邮件格式不正确',
                    'email.max'  => '电子邮件太长了',
                    'email.unique'  => '电子邮件已经被注册过了',
                    'password.required'  => '密码是必填的,最少6个字符',
                    'password.min'  => '密码最少6个字符',
                    'password_confirmation.required'  => '确定密码是必填的,最少6个字符',
                    'password_confirmation.min'  => '确定密码最少是6个字符',
                    'password_confirmation.confirmed'  => '两个密码不一样',
                    'roles.required'  => '角色是必选的',
                    'captcha.required' => '请输入验证码',
                    'captcha.captcha' => '输入的验证码错误',
                ];
                break;
            case 'checkemail':
                return [
                    'name.required' => '名字是必填的',
                    'name.max' => '名字太长了',
                    'email.required'  => '电子邮件是必填的',
                    'email.email'  => '电子邮件格式不正确',
                    'email.max'  => '电子邮件太长了',
                    'email.unique'  => '电子邮件已经被注册过了',
                    'password_confirmation.confirmed'  => '两个密码不一样',
                    'roles.required'  => '角色是必选的',

                ];
                break;
            case 'authuser':
                return [
                    'phone.required' => '电话是必填的',
                    'phone.max' => '电话号码太长',
                    'phone.min' => '电话号码太短',
                    'password.required'  => '密码是必填的,最少6个字符',
                    'password.min'  => '密码最少6个字符',
                    'password_confirmation.required'  => '确定密码是必填的,最少6个字符',
                    'password_confirmation.min'  => '确定密码最少是6个字符',
                    'password_confirmation.confirmed'  => '两个密码不一样',
                    'roles.required'  => '角色是必选的',
                    'captcha.required' => '请输入验证码',
                    'captcha.captcha' => '输入的验证码错误',
                    'confirmterm.required'=>'需要同意用户协议'
                ];
                break;
            case 'login':
                return [
                    'phone.required' => '号码必填',
                    'phone.max' => '号码太长',
                    'password.required'  => '密码必填',
                    'captcha.required' => '请输入验证码',
                    'captcha.captcha' => '输入的验证码错误',
                ];
                break;
            default:
                return [
                    'name.required' => '名字是必填的',
                    'name.max' => '名字太长了',
                    'email.required'  => '电子邮件是必填的',
                    'email.email'  => '电子邮件格式不正确',
                    'email.max'  => '电子邮件太长了',
                    'email.unique'  => '电子邮件已经被注册过了',
                    'password_confirmation.confirmed'  => '两个密码不一样',
                    'roles.required'  => '角色是必选的',
                ];
                break;
        }
    }

    public function role(Request $request, $role_id)
    {
        $auth = $request->user();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            $roles = Role::all();

            $users = Role::find($role_id)->users()->paginate(10);
            return view('users/role', ['users'=>$users, 'usergroups'=>$roles]);
        }

        return redirect('/');
    }
    public function rolemanage(Request $request)
    {
        $auth = $request->user();
//        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
////        if ($authView) {
//            $roles = Role::all();
//
//            $users = User::paginate(10);
//            return view('users/rolemanager', ['users'=>$users, 'usergroups'=>$roles]);
//        }
//
//        return redirect('/');

        return view('users/rolemanage');
    }

    public function side(Request $request)
    {
        $auth = $request->user();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            $roles = Role::all();
            return view('users.side', ['usergroups'=>$roles]);
        } else {
            return redirect('admin/dashboard');
        }
    }

    public function listAutheditor($role_id){
        $authUsers = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_roles.role_id', '=', $role_id)
//            ->paginate(15);
        ->get();

        return view('users/listAutheditor', ['authUsers'=> $authUsers]);
    }

    public function editpw(Request $request, $user_id)
    {
        $auth = $request->user();
        $statuses = UserStatus::all();
        $roles = Role::where('name','<>', 'super_admin')->get();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView || $auth->id == $user_id) {
            return view('users/editpw', ['roles'=>$roles, 'usergroups'=>$roles, 'statuses'=>$statuses]);
        }
        return redirect('/');
        return view('users/editpw', ['user'=>$user]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authEditorList(Request $request)
    {
        $auth = $request->user();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
//        if ($authView) {
            $roles = Role::all();
            $users = User::paginate(10);
            return view('users/authEditorList', ['users'=>$users, 'usergroups'=>$roles]);
//        }
//
//        return redirect('/');
    }


    public function autheditorStore(Request $request)
    {
        $valideType = 'authuser';
        $validator = $this->validator($request->all(), $valideType);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        $user = new User();
        $user->name = $request['phone'];
        $user->email = $request['phone'];
        $user->phone = $request['phone'];
        $user->password = bcrypt($request['password']);
        $user->save();
        $user->roles()->attach(Role::where('name', 'auth_editor')->first());
        Auth::attempt(array('name'=>$user->name, 'password' => $request['password']), false);
        return redirect()->route('authprofile/create');
//        return view('authprofile.create');
    }

    public function authlogin(Request $request){
        $valideType = 'login';
//        dd($request->all());
        $validator = $this->validator($request->all(), $valideType);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $username = $request['phone'];
        $password = $request['password'];

        if (Auth::attempt(array('name'=>$username, 'password' => $password), false)) {
            return Redirect::to('/');
        } else {
            return Redirect::to('authlogin')->with('login_errors', "用户名或密码不正确");
        }
    }
}
