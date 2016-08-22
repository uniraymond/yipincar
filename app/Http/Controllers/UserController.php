<?php

namespace App\Http\Controllers;

use App\Role;
use App\UserRoles;
use Illuminate\Http\Request;

use App\Http\Requests;


use App\User;
use App\Profile;

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
            $users = User::paginate(10);
            return view('users/index', ['users'=>$users]);
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
        $roles = Role::all();
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            return view('users/create', ['roles'=>$roles]);
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
        $roleIds = $request['roles'];
        $new_user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);
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
    public function show($id)
    {
        //
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
        $authView = $auth->hasAnyRole(['super_admin', 'admin']);
        if ($authView) {
            $roles = Role::all();
            $user = User::findorFail($id);
            return view('users/edit', ['user'=> $user, 'roles'=>$roles]);
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
            $currentUser->email = $request['email'];
            if ($request['password']) {
                $currentUser->password = bcrypt($request['password']);
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

        $request->session()->flash('status', 'User: '. $userName .' has been removed!');
        return redirect('admin/user');
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
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }
}
