@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">创建用户</div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'admin/user/', 'class' => 'form form-horizontal', 'method'=>'post')) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', '名字', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-6">
                            {!! Form::text('name', '' , array('class'=>'form-control', 'placeholder' => '名字', 'required'=>'required' )) !!}

                            @if ($errors->has('name'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', '电子邮件(用于登录)', array('class'=>'col-md-4 control-label')) !!}

                        <div class="col-md-6">
                            {!! Form::text('email', '' , array('class'=>'form-control', 'placeholder' => '电子邮件', 'required'=>'required' )) !!}

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">密码</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" placeholder="密码" required >
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation" class="col-md-4 control-label">确认密码</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required >

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                        {!! Form::label('roles', '角色', array('class'=>'col-md-4 control-label')) !!}

                        <div class="col-md-6">
                            <select multiple="multiple" name="roles[]" id="roles" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" >{{ $role->description }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('roles'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('roles') ? ' has-error' : ''}}">
                        <label for="captcha" class="col-md-4 control-label">{!! captcha_img() !!}</label>
                        <div class="col-md-6">
                            <input type="text" name="captcha" />
                            @if ($errors->has('captcha'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('captcha') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    {!! Form::submit('保存', array('class'=>'btn btn-primary col-lg-offset-10 col-md-offset-10 col-sm-offset-10')) !!}
                    {!! Form::token() !!}
                    {!! Form::close() !!}
                </div>
            </div>
            </div>
        </div>
        @include('users.side',['usergroups'=>$usergroups])
     </div>
@endsection