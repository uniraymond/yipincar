@extends('layouts.base')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                还没有填写个人信息，点击
                @if($profile)
                    <a href="{{ url('authprofile/create') }}" >跳转</a>按钮完成个人信息.
                @else
                    <a href="{{ url('admin/profile/'.Auth::user()->id.'/authcreate') }}" >跳转</a>按钮完成个人信息.
                @endif
            </div>
        </div>
    </div>
@endsection