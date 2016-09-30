<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
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
            return view('profiles/show', ['user'=>$user, 'profile' => $userProfile]);
        } else {
            $request->session()->flash('warning', '还没有创建用户资料，请创建用户资料');
            if ($auth && ($auth->hasAnyRole(['super_admin', 'admin']) || $auth->id == $id)) {
                return view('profiles/create', ['user'=>$user]);
            }
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
        
        return view('profiles/authindex');
    }

    public function authcreate(Request $request, $userId)
    {

    }

    public function authedit(Request $request, $userId)
    {

    }

    public function authshow(Request $request, $userId)
    {

    }

    public function authstore(Request $request, $userId)
    {

    }

    public function authupdate(Request $request, $userId)
    {

    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $new=false, $checkemail=true)
    {
        if ($new) {
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages($new));
        } elseif($checkemail){
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages());
        } else{
            return Validator::make($data, [
                'name' => 'required|max:255',
            ], $this->messages());
        }

    }

    public function messages($new=false)
    {
        if($new) {
            return [
                'name.required' => '名字是必填的',
                'name.max' => '名字太长了',
            ];
        } else{
            return [
                'name.required' => '名字是必填的',
                'name.max' => '名字太长了',

            ];
        }
    }
}
