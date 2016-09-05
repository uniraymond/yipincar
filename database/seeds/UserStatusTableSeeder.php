<?php

use Illuminate\Database\Seeder;
use App\UserStatus;

class UserStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userStatus = new UserStatus();
        $userStatus->name = '待审';
        $userStatus->title = 'pending';
        $userStatus->description = '等待审查';
        $userStatus->save();

        $userStatus = new UserStatus();
        $userStatus->name = '审核';
        $userStatus->title = 'checking';
        $userStatus->description = '审核中';
        $userStatus->save();

        $userStatus = new UserStatus();
        $userStatus->name = '激活';
        $userStatus->title = 'active';
        $userStatus->description = '激活';
        $userStatus->save();

        $userStatus = new UserStatus();
        $userStatus->name = '屏蔽';
        $userStatus->title = 'banned';
        $userStatus->description = '屏蔽';
        $userStatus->save();

        $userStatus = new UserStatus();
        $userStatus->name = '删除';
        $userStatus->title = 'deleted';
        $userStatus->description = '删除';
        $userStatus->save();
    }
}
