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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
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
        $new_user->secret = md5($request['password']);
        $new_user->status_id = $request['status_id'];
        $new_user->pre_status_id = $request['status_id'];
        if($request['status_id'] == 4) {
            $new_user->banned = 1;
        }
        $new_user->save();

        foreach($roleIds as $roleId) {
            $new_user->roles()->attach($roleId);
        }
        $old = umask(0);
        $path = public_path().'/photos/' . $new_user->id;
        File::makeDirectory($path, $mode = 0777, true, true);
        $pathoriginal = public_path().'/photos/' . $new_user->id.'/original';
        File::makeDirectory($pathoriginal, $mode = 0777, true, true);
        $paththumbs = public_path().'/photos/' . $new_user->id.'/thumbs';
        File::makeDirectory($paththumbs, $mode = 0777, true, true);

        $pathprofile = public_path().'/photos/profiles/users/' . $new_user->id;
        $pathoriginalprofile = public_path().'/photos/profiles/users/' . $new_user->id.'/original';
        $paththumbsprofile = public_path().'/photos/profiles/users/' . $new_user->id.'/thumbs';

        File::makeDirectory($pathprofile, $mode = 0777, true, true);
        File::makeDirectory($pathoriginalprofile, $mode = 0777, true, true);
        File::makeDirectory($paththumbsprofile, $mode = 0777, true, true);
        umask($old);

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
                $currentUser->secret = md5($request['password']);
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

    public function authbanned(Request $request, $id)
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
        return redirect('admin/user/authEditorList');
    }

    public function authactive(Request $request, $id)
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
        return redirect('admin/user/authEditorList');
    }

    public function authdestroy(Request $request, $id)
    {
        $user = User::find($id);
        $userName = $user->name;
        $user->delete();

        $request->session()->flash('status', '用户: '. $userName .' 被成功删除了.');
        return redirect('admin/user/authEditorList');
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
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                    'roles' => 'required',
                    'captcha' => 'required|captcha'
                ], $this->messages($valideType));
                break;
            case 'checkemail':
                return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'password' => 'confirmed',
                    'roles' => 'required',
                ], $this->messages($valideType));
                break;
            case 'authuser':
                return Validator::make($data, [
//                    'phone' => 'required|digits:11|regex:^0?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$',
//                    'phone' => 'required|digits:11|digits_between:13000000000,19000000000',
                    'phone' => ['required', 'digits:11', 'regex:/^0?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/'],
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
//                    'captcha' => 'required|captcha',
                    'confirmterm' => 'required'
                ], $this->messages($valideType));
                break;
            case 'login':
                return Validator::make($data, [
                    'phone' => ['required', 'digits:11', 'regex:/^0?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/'],
                    'password' => 'required',
                    'captcha' => 'required|captcha',
                ], $this->messages($valideType));
                break;
            case 'resetpw':
                Validator::extend('oldpassword', function ($attribute, $value, $parameters) {
                    return Hash::check($value, Auth::user()->password);
                });
                return Validator::make($data, [
                    'oldpassword' => 'required|oldpassword:' . Auth::user()->password,
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
                    'captcha' => 'required|captcha'
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
                    'phone.digits' => '请输入11位电话号码',
//                    'phone.digits_between' => '请输入正确的手机号码，目前只支持中国大陆手机号码',
                    'phone.regex' => '请输入正确的手机号码，目前只支持中国大陆手机号码',
                    'password.required'  => '密码是必填的,最少6个字符',
                    'password.min'  => '密码最少6个字符',
                    'password_confirmation.required'  => '确定密码是必填的,最少6个字符',
                    'password_confirmation.min'  => '确定密码最少是6个字符',
                    'password_confirmation.confirmed'  => '两个密码不一样',
                    'roles.required'  => '角色是必选的',
//                    'captcha.required' => '请输入验证码',
//                    'captcha.captcha' => '输入的验证码错误',
                    'confirmterm.required'=>'需要同意用户协议'
                ];
                break;
            case 'login':
                return [
                    'phone.required' => '号码必填',
                    'phone.max' => '号码太长',
                    'phone.regex' => '电话号码不正确',
                    'phone.digits_between' => '请输入正确的手机号码，目前只支持中国大陆手机号码',
                    'password.required'  => '密码必填',
                    'captcha.required' => '请输入验证码',
                    'captcha.captcha' => '输入的验证码错误',
                ];
                break;
            case 'resetpw':
                return [
                    'oldpassword.required' => '请填写原密码',
                    'oldpassword.oldpassword' => '原密码不正确',
                    'password.required'  => '密码是必填的,最少6个字符',
                    'password.min'  => '密码最少6个字符',
                    'password.confirmed'  => '两个密码不一样',
                    'password_confirmation.required'  => '确定密码是必填的,最少6个字符',
                    'password_confirmation.min'  => '确定密码最少是6个字符',
                    'password_confirmation.same'  => '两个密码不一样',
                    'captcha.required' => '请输入验证码',
                    'captcha.captcha' => '输入的验证码错误',
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
            ->orderBy('users.id', 'desc')
//            ->paginate(15)
        ->get();

        return view('users/listAutheditor', ['authUsers'=> $authUsers]);
    }

    public function editpw(Request $request, $user_id)
    {
        $auth = $request->user();
        $statuses = UserStatus::all();
        $roles = Role::where('name','<>', 'super_admin')->get();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);

        return view('users/editpw', ['user_id'=>$user_id]);
    }

    public function resetpw(Request $request){
        $auth = $request->user();
        $valideType = 'resetpw';
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);

        $validator = $this->validator($request->all(), $valideType);

        $user_id = $request['user_id'];
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if ($authView || $auth->id == $user_id) {
            $user = User::findorFail($user_id);
            $user->password = bcrypt($request['password']);
            $user->secret = md5($request['password']);
            $user->save();

            $request->session()->flash('status', '密码已经被更改成功');
            return redirect('/');
        }
    }

    public function autheditpw(Request $request, $user_id)
    {
        $auth = $request->user();
        $statuses = UserStatus::all();
        $roles = Role::where('name','<>', 'super_admin')->get();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView || $auth->id == $user_id) {
            if ($auth->hasAnyRole(['auth_editor'])) {
                return view('users/autheditpw');
            }
            return view('users/editpw', ['roles'=>$roles, 'usergroups'=>$roles, 'statuses'=>$statuses]);
        }
        return redirect('/');
        return view('users/autheditpw', ['user'=>$user]);
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
//            $users = User::with(['user_roles'])->where('role_id', 6)
//            ->paginate(10);
        $users = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_roles.role_id', '=', 6)
            ->orderBy('users.id', 'desc')
            ->paginate(12);
//            ->get();
//        dd($users);
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
//        $user->email = null;
        $user->phone = $request['phone'];
        $user->password = bcrypt($request['password']);
        $user->secret = md5($request['password']);
        $user->status_id = 2;
        $user->save();
        $user->roles()->attach(Role::where('name', 'auth_editor')->first());
        Auth::attempt(array('phone'=>$user->phone, 'password' => $request['password']), false);

        $old = umask(0);
        $path = public_path().'/photos/' . $user->id;
        File::makeDirectory($path, $mode = 0777, true, true);
        $pathoriginal = public_path().'/photos/' . $user->id.'/original';
        File::makeDirectory($pathoriginal, $mode = 0777, true, true);
        $paththumbs = public_path().'/photos/' . $user->id.'/thumbs';
        File::makeDirectory($paththumbs, $mode = 0777, true, true);

        $pathprofile = public_path().'/photos/profiles/autheditors/' . $user->id;
        $pathoriginalprofile = public_path().'/photos/profiles/autheditors/' . $user->id.'/original';
        $paththumbsprofile = public_path().'/photos/profiles/autheditors/' . $user->id.'/thumbs';

        File::makeDirectory($pathprofile, $mode = 0777, true, true);
        File::makeDirectory($pathoriginalprofile, $mode = 0777, true, true);
        File::makeDirectory($paththumbsprofile, $mode = 0777, true, true);

        umask($old);
        
        return redirect()->to('authprofile/create');
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
        $phone = $request['phone'];
        $password = $request['password'];

        if (Auth::attempt(array('phone'=>$phone, 'password' => $password), false)) {
            return Redirect::to('/');
        } else {
            return Redirect::to('authlogin')->with('login_errors', "用户名或密码不正确");
        }
    }

    /*--------------------------------
功能:		使用smsapi.fun.php功能函数发送短信示例
说明:		http://api.sms.cn/sms/?ac=send&uid=用户账号&pwd=MD5位32密码&mobile=号码&content=内容
官网:		www.sms.cn
状态:		{"stat":"100","message":"发送成功"}

	100 发送成功
	101 验证失败
	102 短信不足
	103 操作失败
	104 非法字符
	105 内容过多
	106 号码过多
	107 频率过快
	108 号码内容空
	109 账号冻结
	112	号码错误
	116 禁止接口发送
	117 绑定IP不正确
	161 未添加短信模板
	162 模板格式不正确
	163 模板ID不正确
	164 全文模板不匹配
--------------------------------*/
    public function cellphonevalidate($phone){
        //用户账号
        $uid = 'yipinqiche0225';
//MD5密码
        $pwd = 'yipinqiche20160225';

        /*
        * 变量模板发送示例
        * 模板内容：您的验证码是：{$code}，对用户{$username}操作绑定手机号，有效期为5分钟。如非本人操作，可不用理会。【云信】
        * 变量模板ID：100003
        */

//变量模板ID
        $template = '390807';
//6位随机验证码
        $code = $this->randNumber();

        $user = User::where('phone', $phone)->first();
        if ($user && count($user)>0) {
            $messageSent = array('phone'=>$phone, 'code'=>$code, 'status'=>400);
            return json_encode($messageSent);
        }
//短信内容参数
        $contentParam = array(
            'code'		=> $code,
            'username'	=> $phone
        );
//即时发送
        $res = $this->sendSMS($uid,$pwd,$phone,$this->array_to_json($contentParam),$template);
        $messageSent = array();
//        $messageSent = array('phone'=>$phone, 'code'=>$code, 'status'=>100);
        $messageSent = array('phone'=>$phone, 'code'=>$code, 'status'=>$res['stat']);

        return response()->json($messageSent);
//        if( $res['stat']=='100' )
//        {
//            return json_encode()
//            echo "发送成功!";
//        }
//        else
//        {
//            echo "发送失败! 状态：".$res['stat'].'|'.$res['message'];
//        }
    }

    /**
    SMS短信发送函数
    @author		sms.cn
    @link		http://www.sms.cn
     */

    /**
     * 短信发送
     *
     * @param string $uid 短信账号
     * @param string $pwd MD5接口密码
     * @param string $mobile 手机号码
     * @param string $content 短信内容
     * @param string $template 短信模板ID
     * @return array
     */
    function sendSMS($uid,$pwd,$mobile,$content,$template='')
    {
        $apiUrl = 'http://api.sms.cn/sms/';		//短信接口地址
        $data = array(
            'ac' =>		'send',
            'uid'=>		$uid,					//用户账号
            'pwd'=>		md5($pwd.$uid),					//MD5位32密码,密码和用户名拼接字符
            'mobile'=>	$mobile,				//号码
            'content'=>	$content,				//内容
            'template'=>$template,				//变量模板ID 全文模板不用填写
            'format' => 'json',					//接口返回信息格式 json\xml\txt
        );

        $result = $this->postSMS($apiUrl,$data);			//POST方式提交
        $re = $this->json_to_array($result);			    //JSON数据转为数组
        //$re = getSMS($apiUrl,$data);				//GET方式提交

        return $re;
        /*
        if( $re['stat']=='100' )
        {
            return "发送成功!";
        }
        else if( $re['stat']=='101')
        {
            return "验证失败! 状态：".$re;
        }
        else
        {
            return "发送失败! 状态：".$re;
        }
        */
    }

    /*
    //密码直接写的函数里

    function sendSMS($mobile,$content,$template='')
    {
        $uid = 'test';
        $pwd = 'testpass';
        $apiUrl = 'http://api.sms.cn/sms/';		//短信接口地址
        $data = array(
            'ac' =>		'send',
            'uid'=>		$uid,					//用户账号
            'pwd'=>		md5($pwd.$uid),					//MD5位32密码,密码和用户名拼接字符
            'mobile'=>	$mobile,				//号码
            'content'=>	$content,				//内容
            'template'=>$template,				//变量模板ID 全文模板不用填写
            'format' => 'json',					//接口返回信息格式 json\xml\txt
            );

        $result = postSMS($apiUrl,$data);			//POST方式提交
        $re = json_to_array($result);			    //JSON数据转为数组

        if( $re['stat']=='100' )
        {
            return "发送成功";
        }
        else if( $re['stat']=='101')
        {
            return "验证失败! 状态：".$re;
        }
        else
        {
            return "发送失败! 状态：".$re;
        }
    }
    */

    /**
     * POST方式HTTP请求
     *
     * @param string $url URL地址
     * @param array $data POST参数
     * @return string
     */
    function postSMS($url,$data='')
    {
        $row = parse_url($url);
        $host = $row['host'];
        $port='';
        $port = isset($row['port']) ? $row['port']:80;
        $file = $row['path'];
        $post='';
        while (list($k,$v) = each($data))
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }
    /**
     * GET方式HTTP请求
     *
     * @param string $url URL地址
     * @param array $data POST参数
     * @return string
     */
    function getSMS($url,$data='')
    {
        $get='';
        while (list($k,$v) = each($data))
        {
            $get .= $k."=".urlencode($v)."&";	//转URL标准码
        }
        return file_get_contents($url.'?'.$get);
    }
//数字随机码
    function randNumber($len = 6)
    {
        $chars = str_repeat('0123456789', 10);
        $chars = str_shuffle($chars);
        $str   = substr($chars, 0, $len);
        return $str;
    }
//把数组转json字符串
    function array_to_json($p)
    {
        return urldecode(json_encode($this->json_urlencode($p)));
    }
//url转码
    function json_urlencode($p)
    {
        if( is_array($p) )
        {
            foreach( $p as $key => $value )$p[$key] = $this->json_urlencode($value);
        }
        else
        {
            $p = urlencode($p);
        }
        return $p;
    }

//把json字符串转数组
    function json_to_array($p)
    {
        if( mb_detect_encoding($p,array('ASCII','UTF-8','GB2312','GBK')) != 'UTF-8' )
        {
            $p = iconv('GBK','UTF-8',$p);
        }
        return json_decode($p, true);
    }

    public function varifyStatus($user_id){
        $user = User::findorFail($user_id);
        $user->status_id = 3;
        $user->save();

        $messageSent = array('status'=>'actived');
        return response()->json($messageSent);
    }

    public function devarifyStatus(Request $request, $user_id){
        $user = User::findorFail($user_id);
        $user->status_id = 0;
        if($request['reason']) {
            $user->note = $request['reason'];
        }
        $user->save();

        $messageSent = array('status'=>'unactived');
        return response()->json($messageSent);
    }

    public function getLogout(){
        if (Auth::user()->hasAnyRole(['auth_editor'])) {
            Auth::logout();
            return redirect('authlogin');
        } else {
            Auth::logout();
            return redirect('login');
        }
    }

    /**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0777, $recursive = false, $force = false)
    {
        if ($force)
        {
            return @mkdir($path, $mode, $recursive);
        }
        else
        {
            return mkdir($path, $mode, $recursive);
        }
    }
}
