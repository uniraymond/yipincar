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

                        <div class="clearfix">
                            <div class="col-md-6">
                                自媒体类型：
                            </div>
                            <div class="col-md-6">
                                @if($profile->media_type_id == 1)
                                    个体自媒体
                                @elseif($profile->media_type_id == 2)
                                    组织机构自媒体
                                @endif
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                运营者证件：
                            </div>

                            <div class="col-md-6">
                                @if($profile->prove_type == 'sfz')
                                    身份证
                                @elseif($profile->prove_type == 'passport')
                                    护照
                                @endif
                                （{{ $profile->prove_number }}）
{{--                                {{ date('Y-m-d', strtotime($profile->dob)) }}--}}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                证件照片 ：
                            </div>
                            <div class="col-md-6">
                                {{--<img src="{{  url($defaultImage) }}" width="100" />--}}
                                <img src="{{ $profile->prove_resource ? url($profile->prove_resource) : '' }}" width="100" class="profile_id_image" />
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                所在地：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->cityName }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                联系邮箱：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->email }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                联系手机：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->cellphone }}
                            </div>
                        </div>

                        {{--<div class="clearfix">--}}
                            {{--<div class="col-md-6">--}}
                                {{--个人授权书：--}}
                            {{--</div>--}}
                            {{--<div class="col-md-6">--}}
                                {{--<img id="auth_resource_image" width="100" src="{{ $profile->auth_resource ? url($profile->auth_resource) : '' }}" />--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        @if($profile->media_type_id == 2)
                            <div class="clearfix">
                                <div class="col-md-6">
                                    组织机构代码证：
                                </div>
                                <div class="col-md-6">
                                    <img id="ass_resource_image" width="100" src="{{ $profile->ass_resource ? url($profile->ass_resource) : '' }}" />
                                </div>
                            </div>
                        @endif


                        <div class="clearfix">
                            <div class="col-md-6">
                                合同授权书：
                            </div>
                            <div class="col-md-6">
                                <img id="contract_auth_image" width="100" src="{{ $profile->contract_auth ? url($profile->contract_auth) : '' }}" />
                            </div>
                        </div>

                        {{--<div class="clearfix">--}}
                            {{--<div class="col-md-6">--}}
                                {{--专注领域：--}}
                            {{--</div>--}}
                            {{--<div class="col-md-6">--}}
                                {{--{{ $profile->targetArea }}--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="clearfix">
                            <div class="col-md-6">
                                个人网站：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->self_url }}
                            </div>
                        </div>

                        {{--<div class="clearfix">--}}
                            {{--<div class="col-md-6">--}}
                                {{--辅助材料：--}}
                            {{--</div>--}}
                            {{--<div class="col-md-6">--}}
                                {{--{{ $profile->icon_uri }}--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="clearfix">
                            <div class="col-md-6">
                                微信公众号：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->weixin_public_id }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                自媒体名称：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->media_name }}
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                自媒体头像：
                            </div>
                            <div class="col-md-6">
                                <img id="media_icon_image" width="100" src="{{ $profile->media_icon ? url($profile->media_icon) : '' }}"/>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col-md-6">
                                自媒体简介：
                            </div>
                            <div class="col-md-6">
                                {{ $profile->aboutself }}
                            </div>
                        </div>

                        <div clas="clearfix">
                            @if ( Null !== Auth::user() && (Auth::user()->hasAnyRole(['super_admin', 'admin']) || Auth::user()->id == $profile->user_id) )
                                {{--<div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right">--}}
                                    {{--{{ link_to('admin/user/'.$profile->user_id.'/edit', '更改密码', ['class'=>'btn btn-primary']) }}--}}
                                {{--</div>--}}

                                <div class="col-lg-2 col-md-2 col-sm-2 edit_article pull-right">
{{--                                    {{ link_to('/authprofile/'.$profile->user_id.'/edit', '编辑入驻编辑资料', ['class'=>'btn btn-primary']) }}--}}
                                </div>
                            @endif
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection