@extends('layouts.base')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">

            @if ($success = Session::get('status'))
                <div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes">
                    <p class="bg-success">
                        {{ $success }}
                    </p>
                </div>
                <div class="clearfix"></div>
            @endif

            <div class="col-lg-12" style="margin-top: 35px">
                @if(Auth::user()->hasAnyRole(['auth_editor']))
                    还没有填写个人信息，点击<a href="{{ url('authprofile/create') }}" >跳转</a>按钮完成个人信息.
                @else
                    还没有填写个人信息，点击<a href="{{ url('admin/profile/'.Auth::user()->id.'/create') }}" >跳转</a>按钮完成个人信息.
                @endif;
            </div>
        </div>
    </div>
@endsection