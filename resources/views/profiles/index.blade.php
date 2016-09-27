@extends('layouts.base')
{{--@include('users.side',['usergroups'=>$usergroups])--}}
@include('layouts.mineSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                还没有填写个人信息，点击<a href="{{ url('admin/profile/'.Auth::user()->id.'/create') }}" >跳转</a>按钮完成个人信息.
            </div>
        </div>
    </div>
@endsection