<?php

namespace App\Http\Controllers;

use App\City;
use App\Profile;
use App\Province;
use App\Resource;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profiles/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $userId)
    {
        $auth = $request->user();
        if ($auth && ($auth->hasAnyRole(['super_admin', 'admin']) || $auth->id == $userId)) {
            $user = User::findorFail($userId);
            return view('profiles/create', ['user'=>$user]);
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
        $validator = $this->validator($request->all(), $new=true);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $profile = new Profile();
        $profile->name = $request['name'];
        $profile->dob = date('Y-m-d', strtotime($request['dob']));
        $profile->gender = $request['gender'];
        $profile->phone = $request['phone'];
        $profile->address = $request['address'] ? $request['address'] : '自我介绍';
        $profile->cellphone = $request['cellphone'];
        $profile->user_id = $request['user_id'];
        $profile->save();

        $request->session()->flash('status', '成功创建用户资料');
        return redirect('admin/user/');
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
        $userProfile = Profile::where('user_id', $id)->first();
        if (isset($userProfile) && $userProfile) {
            if ($auth && $auth->hasAnyRole(['auth_editor'])) {
                echo 'test';
                return view('profiles/authshow', ['user'=>$user, 'profile' => $userProfile]);
            }
            return view('profiles/show', ['user'=>$user, 'profile' => $userProfile]);
        } else {
            $request->session()->flash('warning', '还没有创建用户资料，请创建用户资料');
            if ($auth && ($auth->hasAnyRole(['super_admin', 'admin']) || $auth->id == $id)) {
                return view('profiles/create', ['user'=>$user]);
            }
//            if ($auth && ($auth->hasAnyRole(['auth_user']))) {
//                return view('authprofile/'.id.'/create');
//            }
            return  redirect('/admin/profile/'.id);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $auth = $request->user();
        $id = $auth->id;
        $user = User::findorFail($id);
        $userProfile = Profile::where('user_id', $id)->first();
        if (isset($userProfile) && $userProfile) {
            if ($user->hasAnyRole('auth_editor')){
                return view('authusers/authprofileshow', ['user'=>$user, 'profile'=>$userProfile]);
            }
            return view('profiles/show', ['user'=>$user, 'profile' => $userProfile]);
        } else {
            $request->session()->flash('warning', '还没有创建用户资料，请创建用户资料');
            if ($auth && ($auth->hasAnyRole(['super_admin', 'admin']) || $auth->id == $id)) {
                return view('profiles/index', ['user'=>$user]);
//                return view('profiles/create', ['user'=>$user]);
            }
            return  redirect('/admin/profile/'.id);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $userId)
    {
        $auth = $request->user();
        if ($auth && ($auth->hasAnyRole(['super_admin', 'admin']) || $auth->id == $userId)) {
            $user = User::findorFail($userId);
//            $userProfile = $user->profiles();
            $userProfile = Profile::where('user_id', $userId)->first();
            if (isset($userProfile)) {
                return view('profiles/edit', ['user'=>$user, 'profile'=>$userProfile]);
            } else {
                return view('profiles/create', ['user'=>$user]);
            }
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
        $profile = Profile::where('user_id', $id)->first();
        $profile->name = $request['name'];
        $profile->dob = $request['dob'];
        $profile->gender = $request['gender'];
        $profile->phone = $request['phone'];
        $profile->address = $request['address'];
        $profile->cellphone = $request['cellphone'];
        $profile->user_id = $request['user_id'];
        $profile->save();

        $request->session()->flash('status', '用户资料成功编辑');
        return redirect('admin/user/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function authindex()
    {
        $auth = $request->user();
        $userId = $auth->id;
        $profile = Profile::where('user_id',$userId)->first();

        if (count($profile)>0) {
            return view('profiles/authindex', ['profile'=>$profile]);
        } else {
            return view('profiles/authindex', ['profile'=>false] );
        }
    }

    public function authcreate(Request $request)
    {
        $auth = $request->user();
        $userId = $auth->id;
        $profile = Profile::where('user_id',$userId)->first();
        $province = Province::all();
//        $cities = City::all();

        if($auth->id == $userId) {
            if (count($profile)<1) {
                return view('authusers/authprofilecreate', ['province'=>$province]);
            } else {
                return view('authusers/authprofileshow', ['profile'=>$profile, 'province'=>$province]);
            }
        } else {
            redirect('/');
        }
    }

    public function authedit(Request $request, $userId)
    {
        $auth = $request->user();
        $profile = Profile::where('user_id', $userId)->first();
        $province = Province::all();
//        $cities = City::all();

        if($auth->id == $userId) {
            if ($profile) {
                return view('authusers/authprofileedit', ['profile'=>$profile, 'province'=>$province]);
            } else {
                return view('authusers/authprofilecreate', ['province'=>$province]);
            }
        } else {
            redirect('/');
        }
    }

    public function authshow(Request $request)
    {
        $auth = $request->user();
        $userId = $auth->id;
        $profile = Profile::where('user_id', $userId)->first();
        $defaultImage = url('/photos/default.png');
        $city = Province::where('id', $profile->city_id)->first();
        $province = Province::all();
        if (count($profile)>0) {
            return view('authusers/authprofileshow', ['profile'=>$profile, 'defaultImage'=>$defaultImage, 'province'=>$city]);
        } else {
            return view('authusers/authprofilecreate', ['defaultImage'=>$defaultImage, 'province'=>$province]);
        }
    }

    public function authstore(Request $request)
    {
        $auth = $request->user();
        $userId = $auth->id;

        $validator = $this->validator($request->all(), $authuser=true);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $proveimage = $request['proveimage'];
        $pimage = $this->uploadfile($proveimage);

        $auth_resource = $request['auth_resource'];
        $authResource = $this->uploadfile($auth_resource);

        $ass_resource = $request['ass_resource'];
        $assResource = $this->uploadfile($ass_resource);

        $contract_auth = $request['contract_auth'];
        $contractAuth = $this->uploadfile($contract_auth);

        $media_icon = $request['media_icon'];
        $mediaIcon = $this->uploadfile($media_icon);

        $profile = new Profile();
        $profile->user_id = $userId;
        $profile->name = $request['name'];
        $profile->media_type_id = $request['mediatype'];
        $profile->prove_type = $request['prove_type'];
        $profile->prove_number = $request['prove_number'];
        if (count($pimage)>0) {
            $profile->prove_resource = $pimage['path'];
        }

        $profile->city_id = $request['city_id'];
        $profile->email = $request['mailbox'];
        $profile->cellphone = $request['cellphone'];

        if (count($authResource)>0) {
            $profile->auth_resource = $authResource['path'];
        }

        if (count($assResource)>0) {
            $profile->ass_resource = $assResource['path'];
        }

        if (count($contractAuth)>0) {
            $profile->contract_auth = $contractAuth['path'];
        }

        if (count($mediaIcon)>0) {
            $profile->media_icon = $mediaIcon['path'];
        }

        $profile->targetArea = $request['targetArea'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->media_name = $request['media_name'];
        $profile->aboutself = $request['about_self'];

        $profile->save();

        $request->session()->flash('status', '成功创建用户资料');
        return redirect('admin/user/');

    }

    public function authupdate(Request $request, $userId)
    {
        $validator = $this->validator($request->all(), $authuser=true);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $proveimage = $request['proveimage'];
        $pimage = $this->uploadfile($proveimage);

        $auth_resource = $request['auth_resource'];
        $authResource = $this->uploadfile($auth_resource);

        $ass_resource = $request['ass_resource'];
        $assResource = $this->uploadfile($ass_resource);

        $contract_auth = $request['contract_auth'];
        $contractAuth = $this->uploadfile($contract_auth);

        $media_icon = $request['media_icon'];
        $mediaIcon = $this->uploadfile($media_icon);

        $profile = Profile::where('user_id', $userId)->get();;
        $profile->name = $request['name'];
        $profile->media_type_id = $request['mediatype'];
        $profile->prove_type = $request['prove_type'];
        $profile->prove_number = $request['prove_number'];
        if (count($pimage)>0) {
            $profile->prove_resource = $pimage['path'];
        }

        $profile->city_id = $request['city_id'];
        $profile->email = $request['mailbox'];
        $profile->cellphone = $request['cellphone'];

        if (count($authResource)>0) {
            $profile->auth_resource = $authResource['path'];
        }

        if (count($assResource)>0) {
            $profile->ass_resource = $assResource['path'];
        }

        if (count($contractAuth)>0) {
            $profile->contract_auth = $contractAuth['path'];
        }

        if (count($mediaIcon)>0) {
            $profile->media_icon = $mediaIcon['path'];
        }

        $profile->targetArea = $request['targetArea'];
        $profile->icon_uri = $request['icon_uri'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->media_name = $request['media_name'];
        $profile->aboutself = $request['about_self'];
        $profile->agree = $request['agree'] ? 1 : 0;

        $profile->save();

        $request->session()->flash('status', '成功创建用户资料');
        return redirect('admin/user/');
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $new=false, $checkemail=true, $authuser = false)
    {
        if ($new) {
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages($new));
        } elseif($checkemail){
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages());
        } elseif($authuser) {
            return Validator::make($data, [
                'phone' => 'required|max:14|min:11',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6',
                'captcha' => 'required|captcha',
                'confirmterm' => 'required'
            ], $this->messages($new, $authuser));
        } else{
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages());
        }

    }

    public function messages($new=false, $authuser= false)
    {
        if($new) {
            return [
                'name.required' => '名字是必填的',
                'name.max' => '名字太长了',
            ];
        } elseif ($authuser) {
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
        } else{
            return [
                'name.required' => '名字是必填的',
                'name.max' => '名字太长了',

            ];
        }
    }

    public function uploadfile($file){
        $a = array();
//        $authuser = $request->user();
        $authuser = 1;
//        $file = $request->file('file');

        if(!empty($file)) {
            $fileName = $file->getClientOriginalName();
//      Storage::put($fileName, file_get_contents($file));
            $fileDir = "photos/autheditor";
            $file->move($fileDir, $fileName);
            $imageLink = $fileDir . '/' . $fileName;

            $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same
            $image = Image::make(sprintf('photos/adv/%s', $file->getClientOriginalName()))->resize(800, (int)((800 * $cell_img_size[1]) / $cell_img_size[0]))->save();

            $resource = new Resource();
            $resource->name = $fileName;
            $resource->link = '/' . $imageLink;
//            $resource->created_by = $authuser->id;
            $resource->save();

            $a = array(
                'filename'=>$fileName,
                'path'=>$resource->link,
//            'userId' => $authuser->id,
            );
        }
        return $a;
    }
}
