@extends('layouts.app')
@include('layouts.settingSideBar')
@section('content')
    <div id="page-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h1>用户权限管理</h1>
                </div>

                {{--new blog link--}}
                {{--@if (Auth::user()->hasAnyRole(['super_admin', 'admin']))--}}
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 pull-right clearfix">--}}
                        {{--{{ link_to('admin/user/create', '添加', ['class'=>'btn btn-secondary']) }}--}}
                    {{--</div>--}}
                {{--@endif--}}

                {{--flash alert--}}
                {{--@if ($success = Session::get('status'))--}}
                    {{--<div class="col-lg-12 col-md-12 col-sm-12 bs-example-bg-classes" >--}}
                        {{--<p class="bg-success">--}}
                            {{--{{ $success }}--}}
                        {{--</p>--}}
                    {{--</div>--}}
                {{--@endif--}}

                {{--@if($users)--}}
                    {{--<table class="table table-striped">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>用户名</th>--}}
                            {{--<th>权限</th>--}}
                            {{--<th>编辑</th>--}}
                            {{--<th>删除</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($users as $user)--}}
                            {{--@if($user->hasRole('super_admin') && !Auth::user()->hasRole('super_admin'))--}}
                            {{--@else--}}
                                {{--<tr>--}}
                                    {{--<td>{{ $user->name }}</td>--}}
                                    {{--<td>--}}
                                        {{--@foreach($user->roles as $ur)--}}
                                            {{--{{ $ur->description }}--}}
                                        {{--@endforeach--}}
                                    {{--</td>--}}
                                    {{--<td><a class="btn btn-default" href="/admin/user/{{ $user->id }}/edit" id="editBtn_{{ $user->id }}">编辑</a></td>--}}
                                    {{--<td>--}}
                                        {{--{!! Form::open(array('url' => 'admin/user/'.$user->id, 'class' => 'form', 'method'=>'delete', 'onsubmit'=>'return confirm("Confirm to delete this user?");')) !!}--}}
                                        {{--{!! Form::text('id', $user->id, array('hidden'=>'hidden', 'readonly' => true)) !!}--}}
                                        {{--{!! Form::submit('删除', array('class'=>'btn btn-primary')) !!}--}}
                                        {{--{!! Form::token() !!}--}}
                                        {{--{!! Form::close() !!}--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--@endif--}}
{{--            @include('users.side',['usergroups'=>$usergroups])--}}
        </div>
    </div>
@endsection