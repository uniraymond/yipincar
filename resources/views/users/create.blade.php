@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">创建用户</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/user', 'class' => 'form', 'method'=>'post')) !!}
                    {!! Form::label('name', '名字', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    {!! Form::text('name', '' , array('class'=>'name input col-lg-12 col-md-12 col-sm-12', 'placeholder' => '名字')) !!}
                    {!! Form::label('email', '电子邮件(用于登录)', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    {!! Form::text('email', '' , array('class'=>'name input col-lg-12 col-md-12 col-sm-12', 'placeholder' => '电子邮件')) !!}
                    {!! Form::label('password', '密码', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    {!! Form::password('password', '', array('class' => 'password col-lg-12 col-md-12 col-sm-12', 'placeholder' => '密码')) !!}
                    {!! Form::label('role', '角色', array('class'=>'col-lg-12 col-md-12 col-sm-12')) !!}
                    <select multiple="multiple" name="roles[]" id="roles" class="col-lg-12 col-md-12 col-sm-12">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" >{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <div class="clearfix"></div>
                    {!! Form::submit('保存', array('class'=>'btn btn-primary col-lg-offset-10 col-md-offset-10 col-sm-offset-10')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
     </div>
@endsection