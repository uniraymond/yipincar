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
        $validator = $this->validator($request->all(), $type = 'new');

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
        $userProfile = Profile::join('provinces', 'profiles.city_id', '=', 'provinces.id')
            ->select('profiles.*', 'provinces.name as cityName') ->where('user_id', $id)->first();
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

    public function authview(Request $request, $userId)
    {
        $auth = $request->user();
//        $userId = $auth->id;
        $city = null;
        $profile = Profile::where('user_id', $userId)->first();
        $defaultImage = 'photos/default.png';
        if($profile){
            $city = Province::where('id', $profile->city_id)->first();
        }
        $province = Province::all();
        if (count($profile)>0) {
            return view('authusers/authprofileview', ['profile'=>$profile, 'defaultImage'=>$defaultImage, 'province'=>$city]);
        } else {
            $request->session()->flash('status', '入驻编辑还未填写资料');
            return redirect('admin/user/authEditorList');
        }
    }

    public function authshow(Request $request)
    {
        $auth = $request->user();
        $userId = $auth->id;
        $profile = Profile::where('user_id', $userId)->first();
        $defaultImage = 'photos/default.png';
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

        $validator = $this->validator($request->all(), $type = 'authuser');

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $proveimage = $request['proveimage'];
        $pimage = $this->uploadfile($proveimage, $userId, 1);

        $auth_resource = $request['auth_resource'];
        $authResource = $this->uploadfile($auth_resource, $userId, 2);

        $ass_resource = $request['ass_resource'];
        $assResource = $this->uploadfile($ass_resource, $userId, 3);

        $contract_auth = $request['contract_auth'];
        $contractAuth = $this->uploadfile($contract_auth, $userId, 4);

        $media_icon = $request['media_icon'];
        $mediaIcon = $this->uploadfile($media_icon, $userId, 5);

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

//        $profile->targetArea = $request['targetArea'];
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

        $validator = $this->validator($request->all(), $type = 'authuseredit');

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $proveimage = $request['proveimage'];
        $pimage = $this->uploadfile($proveimage, $userId, 1);

        $auth_resource = $request['auth_resource'];
        $authResource = $this->uploadfile($auth_resource, $userId, 2);

        $ass_resource = $request['ass_resource'];
        $assResource = $this->uploadfile($ass_resource, $userId, 3);

        $contract_auth = $request['contract_auth'];
        $contractAuth = $this->uploadfile($contract_auth, $userId, 4);

        $media_icon = $request['media_icon'];
        $mediaIcon = $this->uploadfile($media_icon, $userId, 5);

        $profile = Profile::where('user_id', $userId)->first();
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
        // $profile->targetArea = $request['targetArea'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->weixin_public_id = $request['weixin_public_id'];
        $profile->media_name = $request['media_name'];
        $profile->aboutself = $request['about_self'];
        $profile->save();

        $request->session()->flash('status', '成功更改用户资料');
//        return redirect('admin/user/');
        return redirect('/');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $type)
    {
        switch ($type) {
            case 'new':
                return Validator::make($data, [
                    'name' => 'required|max:255',
                ], $this->messages($type));
                break;
            case 'checkemail':
                return Validator::make($data, [
                    'name' => 'required|max:255',
                ], $this->messages($type));
                break;
            case 'authuser':
                return Validator::make($data, [
                    'name' => 'required',
                    'proveimage' => 'required',
                    'city_id' => 'required|not_in:0',
//                    'ass_resource' => 'required',
                    'contract_auth' => 'required',
                    'media_icon' => 'required',
                    'mediatype' => 'required',
                    'prove_number' => 'required',
                    'mailbox' => 'required|email',
                    'weixin_public_id' => 'required',
                    'media_name' => 'required',
                    'cellphone' => 'required|min:11|max:14',
                    'about_self' => 'required|min:4|max:24',
                    'confirmterm' => 'required'
                ], $this->messages($type));
                break;
            case 'authuseredit':
                return Validator::make($data, [
                    'name' => 'required',
                    'city_id' => 'required|not_in:0',
                    'mediatype' => 'required',
                    'prove_number' => 'required',
                    'mailbox' => 'required|email',
                    'weixin_public_id' => 'required',
                    'media_name' => 'required',
                    'cellphone' => 'required|min:11|max:14',
                    'about_self' => 'required|min:4|max:24',
                    'confirmterm' => 'required'
                ], $this->messages($type));
                break;
        }
    }

    public function messages($type)
    {
        switch ($type) {
            case 'new':
                return [
                    'name.required' => '名字是必填的',
                    'name.max' => '名字太长了',
                ];
                break;
            case 'checkemail':
                return [
                    'name.required' => '名字是必填的',
                    'name.max' => '名字太长了',
                ];
                break;
            case 'authuseredit':
            case 'authuser':
                return [
                    'name.required' => '请填写名字',
                    'mediatype.required' => '请选择自媒体类型',
                    'cellphone.required' => '电话是必填的',
                    'cellphone.max' => '电话号码太长',
                    'cellphone.min' => '电话号码太短',
                    'prove_number.required' => '请填写证件号码',
                    'mailbox.required' => '请填写电子邮件地址',
                    'mailbox.email' => '请填写正确电子邮件格式',
                    'weixin_public_id.required' => '请填写微信公众号',
                    'media_name.required' => '请填写媒体名',
                    'about_self.required' => '请填写自媒体简介',
                    'about_self.min' => '自媒体简介最少2个字',
                    'about_self.max' => '自媒体简介最多12个字',
                    'confirmterm.required'=>'需要同意用户协议',
                    'proveimage.required' => '请上传证件照片',
                    'city_id.not_in'=>'请选择所在地城市',
//                    'ass_resource.required' => '请上传组织机构代码证',
                    'contract_auth.required' => '请上传合同授权书',
                    'media_icon.required' => '请上传自媒体头像'
                ];
                break;
        }
    }

    public function uploadfile($file, $authuserId, $type_id = 6){
        $a = array();
        if(!empty($file)) {
            $fileType = strtolower($file->getMimeType());
            $fileName = $file->getClientOriginalName();
            if ($fileType == 'image/jpeg' || $fileType == 'image/jpg' || $fileType == 'image/png' || $fileType == 'image/gif') {
                $fileOriginalDir = "photos/profiles/autheditors/".$authuserId."/original";
                $fileThumbsDir = "photos/profiles/autheditors/".$authuserId."/thumbs";
                $fileDir = "photos/profiles/autheditors/".$authuserId;

                $file->move($fileDir, $fileName);

                $imageOriginalLink = $fileOriginalDir . '/' . $file->getClientOriginalName();
                $imageThumbsLink = $fileThumbsDir . '/' . $file->getClientOriginalName();
                $imageLink = $fileDir . '/' . $file->getClientOriginalName();
                copy($imageLink, $imageThumbsLink);
                copy($imageLink, $imageOriginalLink);

//          $cell_img_size_thumbs = GetImageSize($imageThumbsLink); // need to caculate the file width and height to make the image same
                $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same
                $image = Image::make(sprintf('photos/profiles/autheditors/'.$authuserId.'/%s', $file->getClientOriginalName()))->save();
                $imageThumbs = Image::make(sprintf('photos/profiles/autheditors/'.$authuserId.'/thumbs/%s', $file->getClientOriginalName()))->resize(300, (int)((300 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
                $imageOriginal = Image::make(sprintf('photos/profiles/autheditors/'.$authuserId.'/original/%s', $file->getClientOriginalName()))->save();
            } else {
                $fileDir = "photos/profiles/autheditors/".$authuserId;
                $file->move($fileDir, $fileName);
                $imageLink = $fileDir . '/' . $file->getClientOriginalName();
            }

            $resource = new Resource();
            $resource->name = $fileName;
            $resource->link = '/' . $imageLink;
            $resource->created_by = $authuserId;
            $resource->type_id = $type_id;
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
