@extends('layouts.base')
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" style="margin-top: 35px">
                <h1 class="page-header">个人资料</h1>

                @if ($fail = Session::get('warning'))
                    <div class="col-md-12 bs-example-bg-classes" >
                        <p class="bg-danger">
                            {{ $fail }}
                        </p>
                    </div>
                @endif

                <div class="panel-body">
                    <div class="form-group  col-lg-12 col-md-12 col-sm-12" >
                        <div>
                            <div class="col-md-6">
                                姓名：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->name }}
                            </div>
                        </div>
                        <div>
                            <div class="col-md-6">
                                性别：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->gender }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                生日：
                            </div>
                            <div class="col-md-6">
                                {{ date('Y-m-d', strtotime($profile->dob)) }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                               电话 ：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->phone ? $profile->phone : '无' }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                手机：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->cellphone ? $profile->cellphone : '无' }}
                            </div>
                        </div>

                        <div>
                            <div class="col-md-6">
                                自我介绍：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->address ? $profile->address : '无' }}
                            </div>
                        </div>

                        <div clas="clearfix">
                            @if ( Null !== Auth::user() && (Auth::user()->hasAnyRole(['super_admin', 'admin']) || Auth::user()->id == $profile->user_id) )
                                {{--<div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right">--}}
                                    {{--{{ link_to('admin/user/'.$profile->user_id.'/edit', '更改密码', ['class'=>'btn btn-primary']) }}--}}
                                {{--</div>--}}

                                <div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right">
                                    {{ link_to('admin/profile/'.$profile->user_id.'/edit', '编辑用户资料', ['class'=>'btn btn-primary']) }}
                                </div>
                            @endif
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection