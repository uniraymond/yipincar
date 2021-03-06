<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_roles', 'user_id', 'role_id');
    }
    
    public function userrole()
    {
        return $this->hasMany('App\UserRoles');
    }

    public function profiles()
    {
        return $this->hasOne('App\Profile');
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
    }

    public function userRoles()
    {
        $roles = array();
        $allroles = $this->roles()->get();
        if ($allroles) {
            foreach($allroles  as $role) {
                $roles[$role->id] = $role->name;
            }
        }
        return $roles;
    }

    public function articles()
    {
        return $this->hasMany('App\Article', 'created_by');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'created_by');
    }

    public function filterUserByRoleId($roleId){
        $roles = $this->roles()->where('id', $roleId)->get();
        return $roles;
    }
}
